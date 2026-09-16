<?php

declare(strict_types=1);

/**
 * Variaveis de ambiente
 * Responsavel: Pessoa 1
 */

function loadEnv(string $path): void
{
	if (!is_file($path)) {
		return;
	}

	foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
		$line = trim($line);
		if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
			continue;
		}

		[$name, $value] = explode('=', $line, 2);
		$name = trim($name);
		$value = trim($value);
		$value = trim($value, "\"'");

		if ($name !== '' && getenv($name) === false) {
			putenv($name . '=' . $value);
			$_ENV[$name] = $value;
		}
	}
}

loadEnv(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

function env(string $key, mixed $default = null): mixed
{
	$value = getenv($key);
	return $value === false ? $default : $value;
}
