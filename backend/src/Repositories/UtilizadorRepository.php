<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Utilizador.php';

class UtilizadorRepository
{
	public function __construct(private readonly PDO $connection)
	{
	}

	public function findAll(): array
	{
		throw new LogicException('Repositorio de utilizadores ainda nao implementado');
	}

	public function findById(int $id): ?Utilizador
	{
		throw new LogicException('Repositorio de utilizadores ainda nao implementado');
	}

	public function create(Utilizador $utilizador): Utilizador
	{
		throw new LogicException('Repositorio de utilizadores ainda nao implementado');
	}

	public function update(Utilizador $utilizador): Utilizador
	{
		throw new LogicException('Repositorio de utilizadores ainda nao implementado');
	}

	public function delete(int $id): bool
	{
		throw new LogicException('Repositorio de utilizadores ainda nao implementado');
	}
}
