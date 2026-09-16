<?php

declare(strict_types=1);

class Request
{
	private array $body;

	public function __construct(
		private readonly string $method,
		private readonly string $path,
		private readonly array $headers,
		array $body
	) {
		$this->body = $body;
	}

	public static function capture(): self
	{
		$headers = function_exists('getallheaders') ? getallheaders() : [];
		$rawBody = file_get_contents('php://input') ?: '';
		$body = json_decode($rawBody, true);

		return new self(
			strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
			parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
			array_change_key_case($headers, CASE_LOWER),
			is_array($body) ? $body : $_POST
		);
	}

	public function method(): string
	{
		return $this->method;
	}

	public function path(): string
	{
		return rtrim($this->path, '/') ?: '/';
	}

	public function input(?string $key = null, mixed $default = null): mixed
	{
		if ($key === null) {
			return $this->body;
		}

		return $this->body[$key] ?? $default;
	}

	public function header(string $name, ?string $default = null): ?string
	{
		return $this->headers[strtolower($name)] ?? $default;
	}

	public function bearerToken(): ?string
	{
		$header = $this->header('authorization');
		if ($header !== null && preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
			return $matches[1];
		}

		return null;
	}
}
