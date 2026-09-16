<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Mensagem.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class MensagemRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
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
