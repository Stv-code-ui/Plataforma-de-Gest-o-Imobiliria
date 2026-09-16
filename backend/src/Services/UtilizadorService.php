<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Utilizador.php';
require_once dirname(__DIR__) . '/Repositories/UtilizadorRepository.php';

class UtilizadorService
{
	public function __construct(private readonly UtilizadorRepository $repository)
	{
	}

	public function list(): array
	{
		return $this->repository->findAll();
	}

	public function find(int $id): ?Utilizador
	{
		return $this->repository->findById($id);
	}

	public function create(Utilizador $utilizador): Utilizador
	{
		return $this->repository->create($utilizador);
	}

	public function update(Utilizador $utilizador): Utilizador
	{
		return $this->repository->update($utilizador);
	}

	public function delete(int $id): bool
	{
		return $this->repository->delete($id);
	}
}
