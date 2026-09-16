<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Visita.php';
require_once dirname(__DIR__) . '/Repositories/VisitaRepository.php';

class VisitaService
{
	public function __construct(private readonly VisitaRepository $repository)
	{
	}

	public function list(): array
	{
		return $this->repository->findAll();
	}

	public function find(int $id): ?Visita
	{
		return $this->repository->findById($id);
	}

	public function create(Visita $visita): Visita
	{
		return $this->repository->create($visita);
	}

	public function update(Visita $visita): Visita
	{
		return $this->repository->update($visita);
	}

	public function delete(int $id): bool
	{
		return $this->repository->delete($id);
	}
}
