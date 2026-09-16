<?php

declare(strict_types=1);

interface ContainerInterface
{
	public function bind(string $abstract, string|callable $concrete, bool $singleton = true): void;

	public function get(string $abstract): object;
}
