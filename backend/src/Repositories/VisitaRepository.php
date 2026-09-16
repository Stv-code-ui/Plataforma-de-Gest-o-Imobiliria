<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Visita.php';

class VisitaRepository
{
	public function __construct(private readonly PDO $connection)
	{
	}

	public function findAll(): array
	{
		throw new LogicException('Repositorio de visitas ainda nao implementado');
	}

	public function findById(int $id): ?Visita
	{
		throw new LogicException('Repositorio de visitas ainda nao implementado');
	}

	public function create(Visita $visita): Visita
	{
		throw new LogicException('Repositorio de visitas ainda nao implementado');
	}

	public function update(Visita $visita): Visita
	{
		throw new LogicException('Repositorio de visitas ainda nao implementado');
	}

	public function delete(int $id): bool
	{
		throw new LogicException('Repositorio de visitas ainda nao implementado');
	}
}
