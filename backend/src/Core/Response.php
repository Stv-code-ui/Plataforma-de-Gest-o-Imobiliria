<?php

declare(strict_types=1);

/**
 * Encapsula a resposta JSON
 * Responsavel: Pessoa 1
 */

class Response
{
	public function __construct(
		private readonly array $data,
		private readonly int $status = 200
	) {
	}

	public function send(): never
	{
		http_response_code($this->status);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		exit;
	}

	public static function json(array $data, int $status = 200): self
	{
		return new self($data, $status);
	}

	public static function error(string $message, int $status): never
	{
		(new self(['error' => $message], $status))->send();
	}
}
