<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/ImovelFoto.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class ImovelFotoRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
	}

	public function findAllByImovelId(int $imovelId): array
	{
		$statement = $this->connection->prepare('SELECT id, imovel_id, url FROM imovel_fotos WHERE imovel_id = :imovel_id ORDER BY id');
		$statement->execute(['imovel_id' => $imovelId]);

		return array_map(fn (array $row): ImovelFoto => $this->hydrate($row), $statement->fetchAll());
	}

	public function findById(int $id): ?ImovelFoto
	{
		$statement = $this->connection->prepare('SELECT id, imovel_id, url FROM imovel_fotos WHERE id = :id LIMIT 1');
		$statement->execute(['id' => $id]);
		$row = $statement->fetch();

		return $row === false ? null : $this->hydrate($row);
	}

	public function create(ImovelFoto $foto): ImovelFoto
	{
		$statement = $this->connection->prepare('INSERT INTO imovel_fotos (imovel_id, url) VALUES (:imovel_id, :url)');
		$statement->execute(['imovel_id' => $foto->imovelId, 'url' => $foto->url]);
		$foto->id = (int) $this->connection->lastInsertId();

		return $foto;
	}

	public function update(ImovelFoto $foto): ImovelFoto
	{
		if ($foto->id === null) {
			throw new InvalidArgumentException('A foto precisa de um id para ser atualizada');
		}

		$statement = $this->connection->prepare('UPDATE imovel_fotos SET imovel_id = :imovel_id, url = :url WHERE id = :id');
		$statement->execute(['id' => $foto->id, 'imovel_id' => $foto->imovelId, 'url' => $foto->url]);

		if ($statement->rowCount() === 0 && $this->findById($foto->id) === null) {
			throw new RuntimeException('Foto nao encontrada');
		}

		return $foto;
	}

	public function delete(int $id): bool
	{
		$statement = $this->connection->prepare('DELETE FROM imovel_fotos WHERE id = :id');
		$statement->execute(['id' => $id]);

		return $statement->rowCount() > 0;
	}

	private function hydrate(array $row): ImovelFoto
	{
		return new ImovelFoto((int) $row['imovel_id'], (string) $row['url'], (int) $row['id']);
	}
}