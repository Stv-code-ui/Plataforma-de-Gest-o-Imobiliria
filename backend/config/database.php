<?php

declare(strict_types=1);

/**
 * Ligacao PDO a base de dados
 * Responsavel: Pessoa 1
 */

require_once __DIR__ . '/env.php';

function databaseConnection(): PDO
{
	static $connection = null;

	if ($connection instanceof PDO) {
		return $connection;
	}

	$host = (string) env('DB_HOST', '127.0.0.1');
	$port = (string) env('DB_PORT', '3306');
	$name = (string) env('DB_DATABASE', 'gestao_imobiliaria');
	$user = (string) env('DB_USERNAME', 'root');
	$password = (string) env('DB_PASSWORD', '');

	$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);
	$connection = new PDO($dsn, $user, $password, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);

	return $connection;
}
