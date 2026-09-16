<?php

declare(strict_types=1);

final class AuthUtility
{
	private const SESSION_KEY = 'auth_user';

	public static function hashPassword(string $password): string
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public static function verifyPassword(string $password, string $hash): bool
	{
		return password_verify($password, $hash);
	}

	public static function login(array $user): void
	{
		SessionUtility::regenerate();
		SessionUtility::put(self::SESSION_KEY, $user);
	}

	public static function user(): ?array
	{
		$user = SessionUtility::get(self::SESSION_KEY);
		return is_array($user) ? $user : null;
	}

	public static function id(): ?int
	{
		$user = self::user();
		return isset($user['id']) ? (int) $user['id'] : null;
	}

	public static function check(): bool
	{
		return self::user() !== null;
	}

	public static function guest(): bool
	{
		return !self::check();
	}

	public static function logout(): void
	{
		SessionUtility::forget(self::SESSION_KEY);
		SessionUtility::regenerate();
	}
}