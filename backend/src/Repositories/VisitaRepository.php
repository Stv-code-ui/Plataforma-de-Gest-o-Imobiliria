<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Visita.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class VisitaRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
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
