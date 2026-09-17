<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/autoload.php';

$html = View::make('home', [
	'title' => '<Imóveis>',
	'properties' => [
		['name' => 'Apartamento central', 'location' => 'Luanda', 'price' => '500.000 Kz / mês'],
	],
	'errors' => [],
	'success' => '',
	'formData' => [],
]);

if (!str_contains($html, '&lt;Imóveis&gt;')) {
	throw new RuntimeException('A interpolacao escapada da view falhou');
}
if (!str_contains($html, 'Apartamento central') || !str_contains($html, 'Casa')) {
	throw new RuntimeException('O layout ou o loop da view falhou');
}
if (str_contains($html, '{{ $title }}') || str_contains($html, '@yield')) {
	throw new RuntimeException('A compilacao da view deixou diretivas pendentes');
}

echo "ViewTest: PASS\n";