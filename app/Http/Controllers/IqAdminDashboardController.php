<?php

namespace App\Http\Controllers;

use App\Models\AdminAplicacion;
use App\Models\Iqathletic\Empresa;
use App\Models\Iqathletic\Suscripcion;
use App\Models\Pago;
use App\Models\PlanSuscripcion;
use App\Services\AdminAplicacionRegistrar;
use App\Services\SuscripcionCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class IqAdminDashboardController extends Controller
{
    public function __construct(
        private readonly SuscripcionCheckoutService $checkout,
        private readonly AdminAplicacionRegistrar $registrar,
    ) {}

    public function show(Request $request): Response
    {
        $user = Auth::guard('iqadmin')->user();
        $email = (string) ($user?->email ?? '');
        $empresaId = (int) $request->session()->get('iqadmin_empresa_id');

        if (! $empresaId && $user) {
            $admin = $user->adminPrivilegio();
            $empresaId = $admin?->empresa_id ? (int) $admin->empresa_id : 0;
            if ($empresaId) {
                $request->session()->put('iqadmin_empresa_id', $empresaId);
            }
        }

        $empresaIq = $empresaId
            ? Empresa::query()->where('id', $empresaId)->where('active', 1)->first()
            : null;

        // Mantener sincronizado el vínculo app → empresa en dragonweb.
        if ($email !== '' && $empresaId) {
            $this->registrar->syncFromIqAthleticLogin($email, $empresaId);
        }

        $suscripcion = $empresaId
            ? Suscripcion::query()
                ->where('empresa_id', $empresaId)
                ->orderByDesc('active')
                ->orderByDesc('vencimiento')
                ->orderByDesc('id')
                ->first()
            : null;

        $planes = PlanSuscripcion::query()
            ->where('active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (PlanSuscripcion $plan) => $plan->toDashboardArray())
            ->values();

        $adminLocal = $email !== ''
            ? AdminAplicacion::query()->where('email', $email)->first()
            : null;

        $appIds = is_array($adminLocal?->aplicacion) ? $adminLocal->aplicacion : [];
        if ($appIds === [] && $empresaId) {
            $appIds = [AdminAplicacionRegistrar::APP_IQATHLETIC];
        }

        $catalogoApps = [
            AdminAplicacionRegistrar::APP_IQATHLETIC => [
                'id' => AdminAplicacionRegistrar::APP_IQATHLETIC,
                'slug' => 'iqathletic',
                'nombre' => 'iQ Athletic',
                'hintKey' => 'productHint',
                'icon' => 'iqathletic/icon.png',
            ],
            AdminAplicacionRegistrar::APP_ECOMMERCE => [
                'id' => AdminAplicacionRegistrar::APP_ECOMMERCE,
                'slug' => 'ecommerce',
                'nombre' => 'Ecommerce',
                'hintKey' => 'ecommerceHint',
                'icon' => null,
            ],
        ];

        $aplicaciones = collect($appIds)
            ->map(function ($id) use ($catalogoApps, $adminLocal, $empresaIq) {
                $appId = (int) $id;
                $base = $catalogoApps[$appId] ?? null;
                if (! $base) {
                    return null;
                }

                $empresa = $adminLocal?->empresaDeAplicacion($appId);

                // Fallback en vivo para iQ si aún no hay snapshot guardado.
                if (! $empresa && $appId === AdminAplicacionRegistrar::APP_IQATHLETIC) {
                    $empresa = $this->registrar->snapshotEmpresa($empresaIq);
                }

                if (is_array($empresa)) {
                    $empresa['imagen_url'] = $this->registrar->resolveEmpresaImagenUrl(
                        $empresa['imagen'] ?? null,
                    ) ?? ($empresa['imagen_url'] ?? null);
                }

                return [
                    ...$base,
                    'empresa' => $empresa,
                ];
            })
            ->filter()
            ->values();

        $pagos = $email !== ''
            ? Pago::query()
                ->where('email', $email)
                ->when($empresaId, fn ($q) => $q->where(function ($inner) use ($empresaId) {
                    $inner->where('empresa_id', $empresaId)->orWhereNull('empresa_id');
                }))
                ->orderByDesc('pagado_en')
                ->orderByDesc('id')
                ->limit(50)
                ->get()
                ->map(fn (Pago $pago) => $pago->toDashboardArray())
                ->values()
            : collect();

        return Inertia::render('iqathletic/Dashboard', [
            'admin' => [
                'id' => $user?->id,
                'nombre' => $user?->nombre,
                'email' => $user?->email,
            ],
            'aplicaciones' => $aplicaciones,
            'suscripcion' => $this->checkout->presentacionIq($suscripcion),
            'planes' => $planes,
            'pagos' => $pagos,
            'pagoDisponible' => false,
        ]);
    }
}
