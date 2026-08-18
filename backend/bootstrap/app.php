<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | API Authentication
        |--------------------------------------------------------------------------
        |
        | Jangan redirect request API ke route login.
        | Kalau token tidak valid / tidak ada, API harus mengembalikan
        | response JSON 401.
        |
        */

        $middleware->redirectGuestsTo(function (Request $request) {

            if ($request->is('api/*')) {

                return null;

            }

            return '/login';

        });

    })

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions) {

        /*
        |--------------------------------------------------------------------------
        | API Unauthorized
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            \Illuminate\Auth\AuthenticationException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);

            }

        });

    })

    ->create();