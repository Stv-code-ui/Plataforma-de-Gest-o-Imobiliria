<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Utilizador.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class UtilizadorRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
	}

	public function findAll(): array
	{
		$statement = $this->connection->query('SELECT id, nome, email, senha, telefone, tipo, criado_em FROM utilizadores ORDER BY id');

		return array_map(fn (array $row): Utilizador => $this->hydrate($row), $statement->fetchAll());
	}

	public function findById(int $id): ?Utilizador
	{
		$statement = $this->connection->prepare('SELECT id, nome, email, senha, telefone, tipo, criado_em FROM utilizadores WHERE id = :id LIMIT 1');
		$statement->execute(['id' => $id]);
		$row = $statement->fetch();

		return $row === false ? null : $this->hydrate($row);
	}

	public function create(Utilizador $utilizador): Utilizador
	{
		$statement = $this->connection->prepare('INSERT INTO utilizadores (nome, email, senha, telefone, tipo) VALUES (:nome, :email, :senha, :telefone, :tipo)');
		$statement->execute([
			'nome' => $utilizador->nome,
			'email' => $utilizador->email,
			'senha' => $utilizador->senha,
			'telefone' => $utilizador->telefone,
			'tipo' => $utilizador->tipo,
		]);
		$utilizador->id = (int) $this->connection->lastInsertId();
		$utilizador->criadoEm = date('Y-m-d H:i:s');

		return $utilizador;
	}

	public function update(Utilizador $utilizador): Utilizador
	{
		if ($utilizador->id === null) {
			throw new InvalidArgumentException('O utilizador precisa de um id para ser atualizado');
		}

		$statement = $this->connection->prepare('UPDATE utilizadores SET nome = :nome, email = :email, senha = :senha, telefone = :telefone, tipo = :tipo WHERE id = :id');
		$statement->execute([
			'id' => $utilizador->id,
			'nome' => $utilizador->nome,
			'email' => $utilizador->email,
			'senha' => $utilizador->senha,
			'telefone' => $utilizador->telefone,
			'tipo' => $utilizador->tipo,
		]);

		if ($statement->rowCount() === 0 && $this->findById($utilizador->id) === null) {
			throw new RuntimeException('Utilizador nao encontrado');
		}

		return $utilizador;
	}

	public function delete(int $id): bool
	{
		$statement = $this->connection->prepare('DELETE FROM utilizadores WHERE id = :id');
		$statement->execute(['id' => $id]);

		return $statement->rowCount() > 0;
	}

	private function hydrate(array $row): Utilizador
	{
		return new Utilizador(
			(string) $row['nome'],
			(string) $row['email'],
			(string) $row['senha'],
			$row['telefone'] === null ? null : (string) $row['telefone'],
			(string) $row['tipo'],
			(int) $row['id'],
			$row['criado_em'] === null ? null : (string) $row['criado_em']
		);
	}
}
