<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('panel-adm14') || $request->is('panel-adm14/*')) {
                return route('loginadmin');
            }
            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('user')->check()) {
                return '/panel-adm14/dashboardadmin';
            }
            if (Auth::guard('karyawan')->check()) {
                return '/dashboard';
            }
            return '/';
        });

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 403);
            }

            if ($request->is('panel-adm14') || $request->is('panel-adm14/*')) {
                return redirect()->route('loginadmin')->with(['warning' => 'Sesi Anda telah berakhir, silakan login kembali.']);
            }

            return redirect('/')->with(['warning' => 'Sesi Anda telah berakhir, silakan login kembali.']);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
            if ($e->getStatusCode() === 403) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => $e->getMessage() ?: 'Forbidden',
                    ], 403);
                }

                if ($request->is('panel-adm14') || $request->is('panel-adm14/*')) {
                    return redirect()->route('loginadmin')->with(['warning' => 'Sesi Anda telah berakhir, silakan login kembali.']);
                }

                return redirect('/')->with(['warning' => 'Sesi Anda telah berakhir, silakan login kembali.']);
            }
        });
    })->create();
