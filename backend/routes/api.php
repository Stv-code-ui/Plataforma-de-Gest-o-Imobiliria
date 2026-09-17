<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Core/Contracts/ContainerInterface.php';

function registerApiRoutes(Router $router, ContainerInterface $container): void
{
    $testController = $container->get(TestController::class);

    $router->add('GET', '/api/test', [$testController, 'status']);
    $router->add('POST', '/api/auth/register', function (Request $request) use ($container): Response {
        return $container->get(AuthController::class)->register($request);
    });

    $router->add('POST', '/api/auth/login', function (Request $request) use ($container): Response {
        return $container->get(AuthController::class)->login($request);
    });

    $router->add('GET', '/api/test', function(Request $request) use ($container): Response{
        return Response::json(['data'=>'Ola Mundo']);
    });

    $router->add('GET', '/api/auth/me', function (Request $request) use ($container): Response {
        $authMiddleware = $container->get(AuthMiddleware::class);
        $authController = $container->get(AuthController::class);

        return $authMiddleware->handle(
            $request,
            fn (Request $request, array $auth): Response => $authController->me($request, $auth)
        );
    });
}
