<?php

declare(strict_types=1);

/**
 * Middleware de autenticacao
 * Responsavel: Pessoa 1
 */

require_once dirname(__DIR__) . '/Core/Request.php';
require_once dirname(__DIR__) . '/Core/Response.php';
require_once dirname(__DIR__) . '/Services/AuthService.php';

class AuthMiddleware
{
	public function __construct(private readonly AuthService $authService)
	{
	}

	public function handle(Request $request, callable $next): Response
	{
		$payload = $this->authService->validateToken($request->bearerToken());

		if ($payload === null) {
			return Response::json(['error' => 'Nao autenticado'], 401);
		}

		return $next($request, $payload);
	}
}
