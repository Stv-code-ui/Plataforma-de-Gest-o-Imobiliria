<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Imovel.php';

class ImovelRepository
{
	public function __construct(private readonly PDO $connection)
	{
	}

	public function findAll(): array
	{
		throw new LogicException('Repositorio de imoveis ainda nao implementado');
	}

	public function findById(int $id): ?Imovel
	{
		throw new LogicException('Repositorio de imoveis ainda nao implementado');
	}

	public function create(Imovel $imovel): Imovel
	{
		throw new LogicException('Repositorio de imoveis ainda nao implementado');
	}

	public function update(Imovel $imovel): Imovel
	{
		throw new LogicException('Repositorio de imoveis ainda nao implementado');
	}

	public function delete(int $id): bool
	{
		throw new LogicException('Repositorio de imoveis ainda nao implementado');
	}
}
