<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('iqathletic') || $request->is('iqathletic/*')) {
                return route('iqathletic.login');
            }

            return '/';
        });

        $middleware->redirectUsersTo(function ($request) {
            if ($request->is('iqathletic') || $request->is('iqathletic/*')) {
                return route('iqathletic.dashboard');
            }

            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
