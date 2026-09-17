<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/env.php';
require_once dirname(__DIR__) . '/config/autoload.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/src/Core/Container.php';
require_once dirname(__DIR__) . '/src/Core/Request.php';
require_once dirname(__DIR__) . '/src/Core/Response.php';
require_once dirname(__DIR__) . '/src/Core/Router.php';
require_once dirname(__DIR__) . '/routes/api.php';
require_once dirname(__DIR__) . '/routes/web.php';

header('Access-Control-Allow-Origin: ' . (string) env('CORS_ORIGIN', '*'));
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

	http_response_code(204);
	exit;
}

$request = Request::capture();
$router = new Router();
$container = new Container();
$container->bind(PdoConnectionInterface::class, PdoConnection::class);

registerApiRoutes($router, $container);
registerWebRoutes($router);

try {
	$router->dispatch($request)->send();
} catch (Throwable $exception) {
	if ((bool) env('APP_DEBUG', false)) {
		Response::json(['error' => $exception->getMessage()], 500)->send();
	}
	Response::json(['error' => 'Erro interno do servidor'], 500)->send();
}
