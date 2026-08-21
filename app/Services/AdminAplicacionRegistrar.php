<?php

namespace App\Services;

use App\Models\AdminAplicacion;
use App\Models\Iqathletic\Empresa;
use App\Models\Iqathletic\Suscripcion;

class AdminAplicacionRegistrar
{
    public const APP_IQATHLETIC = 1;

    public const APP_ECOMMERCE = 2;

    /**
     * Check futuro: si el email también es admin de ecommerce.
     * De momento siempre false.
     */
    public function hasEcommerceAdmin(string $email): bool
    {
        return false;
    }

    /**
     * Snapshot futuro de la empresa ecommerce asociada al email.
     *
     * @return array{id:int,nombre:string,email:?string,telefono:?string,direccion:?string,imagen:?string,imagen_url:?string}|null
     */
    public function resolveEcommerceEmpresa(string $email): ?array
    {
        return null;
    }

    /**
     * @return array{id:int,nombre:string,email:?string,telefono:?string,direccion:?string,imagen:?string,imagen_url:?string}|null
     */
    public function snapshotEmpresa(?Empresa $empresa): ?array
    {
        if (! $empresa) {
            return null;
        }

        $imagen = $empresa->imagen ? (string) $empresa->imagen : null;

        return [
            'id' => (int) $empresa->id,
            'nombre' => (string) $empresa->nombre,
            'email' => $empresa->email,
            'telefono' => $empresa->telefono,
            'direccion' => $empresa->direccion,
            'imagen' => $imagen,
            'imagen_url' => $this->resolveEmpresaImagenUrl($imagen),
        ];
    }

    public function resolveEmpresaImagenUrl(?string $imagen): ?string
    {
        if (! $imagen) {
            return null;
        }

        if (str_starts_with($imagen, 'http://') || str_starts_with($imagen, 'https://')) {
            return $imagen;
        }

        $base = (string) config('services.iqathletic.s3_url');
        if ($base === '') {
            return null;
        }

        return $base.'/'.ltrim($imagen, '/');
    }

    /**
     * Tras un login admin válido en iQ Athletic, registra/actualiza el email en dragonweb.
     */
    public function syncFromIqAthleticLogin(string $email, int $empresaId): AdminAplicacion
    {
        $apps = [self::APP_IQATHLETIC];

        if ($this->hasEcommerceAdmin($email)) {
            $apps[] = self::APP_ECOMMERCE;
        }

        $iqEmpresa = Empresa::query()
            ->where('id', $empresaId)
            ->where('active', 1)
            ->first();

        $empresas = [
            (string) self::APP_IQATHLETIC => $this->snapshotEmpresa($iqEmpresa),
        ];

        if (in_array(self::APP_ECOMMERCE, $apps, true)) {
            $empresas[(string) self::APP_ECOMMERCE] = $this->resolveEcommerceEmpresa($email);
        }

        $suscripcion = Suscripcion::query()
            ->where('empresa_id', $empresaId)
            ->orderByDesc('active')
            ->orderByDesc('vencimiento')
            ->orderByDesc('id')
            ->first();

        $payload = [
            'aplicacion' => array_values(array_unique($apps)),
            'empresas' => array_filter(
                $empresas,
                fn ($empresa) => is_array($empresa),
            ),
            'suscripcion' => $suscripcion?->tipo,
            'valor' => $suscripcion?->monto,
            'inicio' => $suscripcion?->inicio,
            'vencimiento' => $suscripcion?->vencimiento,
            'status' => $suscripcion?->status,
        ];

        $record = AdminAplicacion::query()->where('email', $email)->first();

        if (! $record) {
            return AdminAplicacion::query()->create([
                'email' => $email,
                ...$payload,
            ]);
        }

        $mergedApps = array_values(array_unique(array_merge(
            is_array($record->aplicacion) ? $record->aplicacion : [],
            $payload['aplicacion'],
        )));

        $existingEmpresas = is_array($record->empresas) ? $record->empresas : [];
        $mergedEmpresas = $existingEmpresas;

        foreach ($payload['empresas'] as $appKey => $empresaData) {
            $mergedEmpresas[(string) $appKey] = $empresaData;
        }

        // Si ecommerce ya no aplica, no borramos su empresa guardada; solo se muestra si está en apps.

        $record->update([
            'aplicacion' => $mergedApps,
            'empresas' => $mergedEmpresas,
            'suscripcion' => $payload['suscripcion'],
            'valor' => $payload['valor'],
            'inicio' => $payload['inicio'],
            'vencimiento' => $payload['vencimiento'],
            'status' => $payload['status'],
        ]);

        return $record->fresh();
    }
}
