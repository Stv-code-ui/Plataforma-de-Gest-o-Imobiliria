<?php

declare(strict_types=1);

final class SessionUtility
{
	public static function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	public static function put(string $key, mixed $value): void
	{
		self::start();
		$_SESSION[$key] = $value;
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		self::start();
		return $_SESSION[$key] ?? $default;
	}

	public static function has(string $key): bool
	{
		self::start();
		return array_key_exists($key, $_SESSION);
	}

	public static function forget(string $key): void
	{
		self::start();
		unset($_SESSION[$key]);
	}

	public static function flush(): void
	{
		self::start();
		$_SESSION = [];
	}

	public static function regenerate(bool $deleteOldSession = true): void
	{
		self::start();
		session_regenerate_id($deleteOldSession);
	}
}