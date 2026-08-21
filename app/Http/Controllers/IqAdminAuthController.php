<?php

namespace App\Http\Controllers;

use App\Models\Iqathletic\IqUser;
use App\Services\AdminAplicacionRegistrar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class IqAdminAuthController extends Controller
{
    public function showLogin(): Response|RedirectResponse
    {
        if (Auth::guard('iqadmin')->check()) {
            return redirect()->route('iqathletic.dashboard');
        }

        return Inertia::render('iqathletic/Login');
    }

    public function login(Request $request, AdminAplicacionRegistrar $registrar): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var IqUser|null $user */
        $user = IqUser::query()
            ->where('email', $credentials['email'])
            ->where('active', 1)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('Credenciales inválidas.'),
            ]);
        }

        $admin = $user->adminPrivilegio();

        if (! $admin || ! $admin->empresa_id) {
            throw ValidationException::withMessages([
                'email' => __('No tenés privilegios de administrador.'),
            ]);
        }

        $registrar->syncFromIqAthleticLogin(
            $user->email,
            (int) $admin->empresa_id,
        );

        Auth::guard('iqadmin')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('iqadmin_empresa_id', (int) $admin->empresa_id);

        return redirect()->intended(route('iqathletic.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('iqadmin')->logout();
        $request->session()->forget('iqadmin_empresa_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('iqathletic.login');
    }
}
