<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Imovel.php';
require_once dirname(__DIR__) . '/Repositories/ImovelRepository.php';

class ImovelService
{
	public function __construct(private readonly ImovelRepository $repository)
	{
	}

	public function list(): array
	{
		return $this->repository->findAll();
	}

	public function find(int $id): ?Imovel
	{
		return $this->repository->findById($id);
	}

	public function create(Imovel $imovel): Imovel
	{
		return $this->repository->create($imovel);
	}

	public function update(Imovel $imovel): Imovel
	{
		return $this->repository->update($imovel);
	}

	public function delete(int $id): bool
	{
		return $this->repository->delete($id);
	}
}
