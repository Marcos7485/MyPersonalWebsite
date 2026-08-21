<?php

namespace App\Services;

use App\Models\AdminAplicacion;
use App\Models\Iqathletic\IqUser;
use App\Models\Iqathletic\Suscripcion as IqSuscripcion;
use App\Models\PlanSuscripcion;
use Carbon\Carbon;
use RuntimeException;

/**
 * Flujo previsto:
 * 1) Admin elige un plan de dragonweb.suscripciones en el dashboard.
 * 2) Se procesa el pago (pendiente de implementar).
 * 3) Si tiene app iQ Athletic (1), se actualiza iqathletic.suscripciones
 *    por empresa_id: tipificar plan y extender vencimiento (+1 mes / +1 año)
 *    respetando el día del mes.
 *
 * Reglas de estado (igual que iQ Athletic):
 * - 3 días de gracia luego del vencimiento.
 * - Progresivo/Mensual: +1 mes; Anual: +1 año.
 * - Límites alumnos por monto: 50k→20, 150k→40, ≥250k→ilimitado.
 */
class SuscripcionCheckoutService
{
    public function __construct(
        private readonly AdminAplicacionRegistrar $adminRegistrar,
    ) {}

    /**
     * Extiende vencimiento respetando el número de día.
     * Mensual/Progresivo: +N meses. Anual: +N años.
     */
    public function computeNextVencimiento(Carbon $from, PlanSuscripcion $plan): Carbon
    {
        $base = $from->copy()->startOfDay();
        $duracion = max(1, (int) $plan->duracion);

        if ($plan->isYearly()) {
            return $base->addYearsNoOverflow($duracion);
        }

        return $base->addMonthsNoOverflow($duracion);
    }

    /**
     * Días restantes de servicio (incluye gracia negativa hasta 0).
     * Antes del vencimiento: días hasta esa fecha.
     * Después: DIAS_GRACIA - días desde vencimiento.
     */
    public function diasRestantes(?Carbon $vencimiento, ?Carbon $hoy = null): int
    {
        if (! $vencimiento) {
            return 0;
        }

        $hoy = ($hoy ?? Carbon::today())->copy()->startOfDay();
        $venc = $vencimiento->copy()->startOfDay();

        if ($hoy->lt($venc)) {
            return (int) $hoy->diffInDays($venc);
        }

        $diasDesdeVencimiento = (int) $venc->diffInDays($hoy);

        return PlanSuscripcion::DIAS_GRACIA - $diasDesdeVencimiento;
    }

    public function enPeriodoGracia(?Carbon $vencimiento, ?Carbon $hoy = null): bool
    {
        if (! $vencimiento) {
            return false;
        }

        $hoy = ($hoy ?? Carbon::today())->copy()->startOfDay();
        $venc = $vencimiento->copy()->startOfDay();

        return $hoy->gte($venc) && $this->diasRestantes($vencimiento, $hoy) > 0;
    }

    public function servicioCaducado(?Carbon $vencimiento, ?Carbon $hoy = null): bool
    {
        return $this->diasRestantes($vencimiento, $hoy) <= 0;
    }

    /**
     * Payload enriquecido para el dashboard a partir de la fila iqathletic.
     */
    public function presentacionIq(?IqSuscripcion $suscripcion): ?array
    {
        if (! $suscripcion) {
            return null;
        }

        $monto = (int) $suscripcion->monto;
        $dias = $this->diasRestantes($suscripcion->vencimiento);
        $planId = PlanSuscripcion::matchTipo($suscripcion->tipo);

        return [
            'id' => $suscripcion->id,
            'tipo' => $suscripcion->tipo,
            'plan_id' => $planId,
            'status' => $suscripcion->status,
            'monto' => $monto,
            'monto_formato' => $this->formatArs($monto),
            'limite_alumnos' => PlanSuscripcion::limiteAlumnosPorMonto($monto),
            'observaciones' => $suscripcion->observaciones,
            'inicio' => optional($suscripcion->inicio)?->format('d/m/Y'),
            'vencimiento' => optional($suscripcion->vencimiento)?->format('d/m/Y'),
            'inicio_iso' => optional($suscripcion->inicio)?->format('Y-m-d'),
            'vencimiento_iso' => optional($suscripcion->vencimiento)?->format('Y-m-d'),
            'active' => (bool) $suscripcion->active,
            'expired' => $suscripcion->isExpired(),
            'dias_restantes' => max(0, $dias),
            'dias_restantes_raw' => $dias,
            'en_gracia' => $this->enPeriodoGracia($suscripcion->vencimiento),
            'servicio_caducado' => $this->servicioCaducado($suscripcion->vencimiento),
            'dias_gracia' => PlanSuscripcion::DIAS_GRACIA,
        ];
    }

    public function formatArs(int $monto): string
    {
        return '$'.number_format($monto, 0, ',', '.');
    }

    /**
     * Stub de pago. Cuando exista el gateway, devolver true solo si cobró OK.
     */
    public function processPayment(string $email, int $planId, int $monto): bool
    {
        return false;
    }

    /**
     * Tras un pago exitoso: sincroniza iqathletic.suscripciones si el admin tiene app 1.
     * También refresca admin_aplicaciones en dragonweb.
     *
     * @param  int|null  $monto  Si null, usa el precio del plan (o nivel progresivo elegido).
     */
    public function applyAfterPayment(
        string $email,
        int $planId,
        int $empresaId,
        ?int $monto = null,
    ): IqSuscripcion {
        $plan = PlanSuscripcion::query()->where('id', $planId)->where('active', true)->first();

        if (! $plan) {
            throw new RuntimeException('Plan de suscripción inválido.');
        }

        $montoFinal = $monto ?? (int) $plan->precio;

        if (! $this->processPayment($email, $planId, $montoFinal)) {
            throw new RuntimeException('El pago aún no está disponible.');
        }

        $adminLocal = AdminAplicacion::query()->where('email', $email)->first();
        $apps = is_array($adminLocal?->aplicacion) ? $adminLocal->aplicacion : [];

        if (! in_array(AdminAplicacionRegistrar::APP_IQATHLETIC, $apps, true)) {
            throw new RuntimeException('Este administrador no tiene iQ Athletic contratado.');
        }

        $actual = IqSuscripcion::query()
            ->where('empresa_id', $empresaId)
            ->orderByDesc('active')
            ->orderByDesc('vencimiento')
            ->orderByDesc('id')
            ->first();

        $hoy = Carbon::today();
        $inicio = $actual?->inicio?->copy() ?? $hoy;

        $baseVencimiento = $actual?->vencimiento && $actual->vencimiento->greaterThanOrEqualTo($hoy)
            ? $actual->vencimiento->copy()
            : $hoy;

        $nuevoVencimiento = $this->computeNextVencimiento($baseVencimiento, $plan);

        if ($actual) {
            $actual->update([
                'tipo' => $plan->nombre,
                'monto' => $montoFinal,
                'status' => 'activa',
                'inicio' => $inicio,
                'vencimiento' => $nuevoVencimiento,
                'active' => 1,
            ]);
            $suscripcion = $actual->fresh();
        } else {
            $iqUser = IqUser::query()->where('email', $email)->first();

            $suscripcion = IqSuscripcion::query()->create([
                'user_id' => (int) ($iqUser?->id ?? 0),
                'empresa_id' => $empresaId,
                'monto' => $montoFinal,
                'tipo' => $plan->nombre,
                'status' => 'activa',
                'observaciones' => null,
                'inicio' => $hoy,
                'vencimiento' => $nuevoVencimiento,
                'active' => 1,
            ]);
        }

        $this->adminRegistrar->syncFromIqAthleticLogin($email, $empresaId);

        return $suscripcion;
    }
}
