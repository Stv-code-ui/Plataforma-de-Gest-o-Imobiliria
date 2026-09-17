<?php

declare(strict_types=1);

function registerWebRoutes(Router $router): void
{
	$router->add('GET', '/', static function (Request $request): Response {
		return Response::view('home', [
			'title' => 'Imóveis disponíveis',
			'properties' => sampleProperties(),
			'errors' => [],
			'success' => '',
			'formData' => [],
		]);
	});

	$router->add('POST', '/contact', static function (Request $request): Response {
		$formData = [
			'name' => trim((string) $request->input('name', '')),
			'email' => trim((string) $request->input('email', '')),
			'message' => trim((string) $request->input('message', '')),
		];
		$errors = [];

		if ($formData['name'] === '') {
			$errors[] = 'Informe o seu nome.';
		}
		if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Informe um email válido.';
		}
		if ($formData['message'] === '') {
			$errors[] = 'Escreva uma mensagem.';
		}

		return Response::view('home', [
			'title' => 'Imóveis disponíveis',
			'properties' => sampleProperties(),
			'errors' => $errors,
			'success' => $errors === [] ? 'Mensagem recebida pelo backend com sucesso.' : '',
			'formData' => $formData,
		]);
	});
}

function sampleProperties(): array
{
	return [
		['name' => 'Apartamento T3 luminoso', 'location' => 'Talatona, Luanda', 'price' => '850.000 Kz / mês'],
		['name' => 'Moradia familiar com jardim', 'location' => 'Kilamba, Luanda', 'price' => '1.200.000 Kz / mês'],
	];
}