<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Core/Router.php';
require_once dirname(__DIR__) . '/src/Core/Request.php';
require_once dirname(__DIR__) . '/src/Core/Response.php';
require_once dirname(__DIR__) . '/src/Repositories/AuthRepository.php';
require_once dirname(__DIR__) . '/src/Services/AuthService.php';
require_once dirname(__DIR__) . '/src/Controllers/AuthController.php';
require_once dirname(__DIR__) . '/src/Middlewares/AuthMiddleware.php';

function registerApiRoutes(Router $router, AuthController $authController): void
{
    $router->add('POST', '/api/auth/register', [$authController, 'register']);
    $router->add('POST', '/api/auth/login', [$authController, 'login']);

    $authMiddleware = new AuthMiddleware(
        new AuthService(new AuthRepository(databaseConnection()))
    );
    $router->add('GET', '/api/auth/me', function (Request $request) use ($authMiddleware, $authController): Response {
        return $authMiddleware->handle(
            $request,
            fn (Request $request, array $auth): Response => $authController->me($request, $auth)
        );
    });
}
