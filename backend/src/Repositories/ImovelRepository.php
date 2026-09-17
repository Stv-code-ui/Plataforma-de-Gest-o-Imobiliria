<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Imovel.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class ImovelRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
	}

	public function findAll(): array
	{
		$statement = $this->connection->query('SELECT id, proprietario_id, titulo, descricao, tipo, tipologia, preco, localizacao, quartos, verificado, disponivel, criado_em FROM imoveis ORDER BY id');

		return array_map(fn (array $row): Imovel => $this->hydrate($row), $statement->fetchAll());
	}

	public function findById(int $id): ?Imovel
	{
		$statement = $this->connection->prepare('SELECT id, proprietario_id, titulo, descricao, tipo, tipologia, preco, localizacao, quartos, verificado, disponivel, criado_em FROM imoveis WHERE id = :id LIMIT 1');
		$statement->execute(['id' => $id]);
		$row = $statement->fetch();

		return $row === false ? null : $this->hydrate($row);
	}

	public function create(Imovel $imovel): Imovel
	{
		$statement = $this->connection->prepare('INSERT INTO imoveis (proprietario_id, titulo, descricao, tipo, tipologia, preco, localizacao, quartos, verificado, disponivel) VALUES (:proprietario_id, :titulo, :descricao, :tipo, :tipologia, :preco, :localizacao, :quartos, :verificado, :disponivel)');
		$statement->execute($this->parameters($imovel));
		$imovel->id = (int) $this->connection->lastInsertId();
		$imovel->criadoEm = date('Y-m-d H:i:s');

		return $imovel;
	}

	public function update(Imovel $imovel): Imovel
	{
		if ($imovel->id === null) {
			throw new InvalidArgumentException('O imovel precisa de um id para ser atualizado');
		}

		$parameters = $this->parameters($imovel);
		$parameters['id'] = $imovel->id;
		$statement = $this->connection->prepare('UPDATE imoveis SET proprietario_id = :proprietario_id, titulo = :titulo, descricao = :descricao, tipo = :tipo, tipologia = :tipologia, preco = :preco, localizacao = :localizacao, quartos = :quartos, verificado = :verificado, disponivel = :disponivel WHERE id = :id');
		$statement->execute($parameters);

		if ($statement->rowCount() === 0 && $this->findById($imovel->id) === null) {
			throw new RuntimeException('Imovel nao encontrado');
		}

		return $imovel;
	}

	public function delete(int $id): bool
	{
		$statement = $this->connection->prepare('DELETE FROM imoveis WHERE id = :id');
		$statement->execute(['id' => $id]);

		return $statement->rowCount() > 0;
	}

	private function parameters(Imovel $imovel): array
	{
		return [
			'proprietario_id' => $imovel->proprietarioId,
			'titulo' => $imovel->titulo,
			'descricao' => $imovel->descricao,
			'tipo' => $imovel->tipo,
			'tipologia' => $imovel->tipologia,
			'preco' => $imovel->preco,
			'localizacao' => $imovel->localizacao,
			'quartos' => $imovel->quartos,
			'verificado' => (int) $imovel->verificado,
			'disponivel' => (int) $imovel->disponivel,
		];
	}

	private function hydrate(array $row): Imovel
	{
		return new Imovel(
			(int) $row['proprietario_id'],
			(string) $row['titulo'],
			$row['descricao'] === null ? null : (string) $row['descricao'],
			(string) $row['tipo'],
			$row['tipologia'] === null ? null : (string) $row['tipologia'],
			(float) $row['preco'],
			(string) $row['localizacao'],
			(int) $row['quartos'],
			(bool) $row['verificado'],
			(bool) $row['disponivel'],
			(int) $row['id'],
			$row['criado_em'] === null ? null : (string) $row['criado_em']
		);
	}
}
