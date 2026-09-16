<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Repositories/TestRepository.php';

class TestService
{
	public function __construct(private readonly TestRepository $repository)
	{
	}

	public function getRepository(): TestRepository
	{
		return $this->repository;
	}

	public function status(): array
	{
		return [
			'status' => 'ok',
			'service' => self::class,
			'repository' => $this->repository::class,
		];
	}
}
