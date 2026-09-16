<?php

declare(strict_types=1);

final class CookieUtility
{
	public static function set(
		string $name,
		string $value,
		int $minutes = 60,
		bool $httpOnly = true,
		bool $secure = false,
		string $sameSite = 'Lax'
	): void {
		setcookie($name, $value, [
			'expires' => time() + ($minutes * 60),
			'path' => '/',
			'httponly' => $httpOnly,
			'secure' => $secure,
			'samesite' => $sameSite,
		]);
	}

	public static function get(string $name, mixed $default = null): mixed
	{
		return $_COOKIE[$name] ?? $default;
	}

	public static function has(string $name): bool
	{
		return array_key_exists($name, $_COOKIE);
	}

	public static function forget(string $name): void
	{
		setcookie($name, '', [
			'expires' => time() - 3600,
			'path' => '/',
		]);
		unset($_COOKIE[$name]);
	}
}