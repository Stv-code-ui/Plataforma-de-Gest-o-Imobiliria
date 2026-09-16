<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
	$root = dirname(__DIR__);
	$directories = [
		$root . '/config',
		$root . '/src/Core',
		$root . '/src/Core/Contracts',
		$root . '/src/Controllers',
		$root . '/src/Middlewares',
		$root . '/src/Models',
		$root . '/src/Repositories',
		$root . '/src/Services',
	];

	foreach ($directories as $directory) {
		$file = $directory . '/' . $class . '.php';
		if (is_file($file)) {
			require_once $file;
			return;
		}
	}
});
