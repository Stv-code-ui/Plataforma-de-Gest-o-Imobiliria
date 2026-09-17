<?php

declare(strict_types=1);

class Response
{
	public function __construct(
		private readonly mixed $data,
		private readonly int $status = 200,
		private readonly string $contentType = 'application/json; charset=utf-8'
	) {
	}

	public function send(): never
	{
		http_response_code($this->status);
		header('Content-Type: ' . $this->contentType);
		if ($this->contentType === 'application/json; charset=utf-8') {
			echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		} else {
			echo (string) $this->data;
		}
		exit;
	}

	public static function json(array $data, int $status = 200): self
	{
		return new self($data, $status);
	}

	public static function view(string $name, array $data = [], int $status = 200): self
	{
		return new self(View::make($name, $data), $status, 'text/html; charset=utf-8');
	}

	public static function error(string $message, int $status): never
	{
		(new self(['error' => $message], $status))->send();
	}
}
