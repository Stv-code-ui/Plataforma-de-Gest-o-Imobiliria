<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Mensagem.php';

class MensagemRepository
{
	public function __construct(private readonly PDO $connection)
	{
	}

	public function findAll(): array
	{
		throw new LogicException('Repositorio de mensagens ainda nao implementado');
	}

	public function findById(int $id): ?Mensagem
	{
		throw new LogicException('Repositorio de mensagens ainda nao implementado');
	}

	public function create(Mensagem $mensagem): Mensagem
	{
		throw new LogicException('Repositorio de mensagens ainda nao implementado');
	}

	public function update(Mensagem $mensagem): Mensagem
	{
		throw new LogicException('Repositorio de mensagens ainda nao implementado');
	}

	public function delete(int $id): bool
	{
		throw new LogicException('Repositorio de mensagens ainda nao implementado');
	}
}
