<?php

declare(strict_types=1);

/**
 * Router simples (dispatch de rotas)
 * Responsavel: Pessoa 1
 */

class Router
{
	private array $routes = [];

	public function add(string $method, string $path, callable $handler): void
	{
		$this->routes[strtoupper($method)][$path] = $handler;
	}

	public function dispatch(Request $request): Response
	{
		$handler = $this->routes[$request->method()][$request->path()] ?? null;

		if ($handler === null) {
			return Response::json(['error' => 'Rota nao encontrada'], 404);
		}

		return $handler($request);
	}
}
