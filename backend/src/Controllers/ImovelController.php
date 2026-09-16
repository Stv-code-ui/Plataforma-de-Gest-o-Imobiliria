<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Core/Request.php';
require_once dirname(__DIR__) . '/Core/Response.php';
require_once dirname(__DIR__) . '/Services/ImovelService.php';

class ImovelController
{
	public function __construct(private readonly ImovelService $service)
	{
	}

	public function index(Request $request): Response
	{
		return $this->notImplemented();
	}

	public function show(Request $request, int $id): Response
	{
		return $this->notImplemented();
	}

	public function store(Request $request): Response
	{
		return $this->notImplemented();
	}

	public function update(Request $request, int $id): Response
	{
		return $this->notImplemented();
	}

	public function destroy(Request $request, int $id): Response
	{
		return $this->notImplemented();
	}

	private function notImplemented(): Response
	{
		return Response::json(['error' => 'Operacao de imoveis ainda nao implementada'], 501);
	}
}
