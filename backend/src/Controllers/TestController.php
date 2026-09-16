<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Core/Request.php';
require_once dirname(__DIR__) . '/Core/Response.php';
require_once dirname(__DIR__) . '/Services/TestService.php';

class TestController
{
	public function __construct(private readonly TestService $service)
	{
	}

	public function status(Request $request): Response
	{
		return Response::json(['data' => $this->service->status()]);
	}
}