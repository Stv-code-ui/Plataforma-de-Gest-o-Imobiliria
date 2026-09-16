<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/PdoConnection.php';

function databaseConnection(): PDO
{
	static $provider = null;

	$provider ??= new PdoConnection();

	return $provider->getConnection();
}
