<?php

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\SnakeCaseMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            SnakeCaseMiddleware::class,
            EnsureFrontendRequestsAreStateful::class,
            ForceJsonResponse::class
        ]);

        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
//        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Record not found.',
//                'data' => null
//            ], 404);
//        });
//        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Unauthorized.',
//                'data' => null
//            ], 401);
//        });
    })->create();
