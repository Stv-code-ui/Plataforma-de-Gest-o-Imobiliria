<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Favorito.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class FavoritoRepository
{
	private readonly PDO $connection;

	public function __construct(PdoConnectionInterface $provider)
	{
		$this->connection = $provider->getConnection();
	}

	public function findAllByUtilizadorId(int $utilizadorId): array
	{
		$statement = $this->connection->prepare('SELECT utilizador_id, imovel_id FROM favoritos WHERE utilizador_id = :utilizador_id ORDER BY imovel_id');
		$statement->execute(['utilizador_id' => $utilizadorId]);

		return array_map(fn (array $row): Favorito => $this->hydrate($row), $statement->fetchAll());
	}

	public function exists(int $utilizadorId, int $imovelId): bool
	{
		$statement = $this->connection->prepare('SELECT 1 FROM favoritos WHERE utilizador_id = :utilizador_id AND imovel_id = :imovel_id LIMIT 1');
		$statement->execute(['utilizador_id' => $utilizadorId, 'imovel_id' => $imovelId]);

		return $statement->fetchColumn() !== false;
	}

	public function create(Favorito $favorito): Favorito
	{
		$statement = $this->connection->prepare('INSERT INTO favoritos (utilizador_id, imovel_id) VALUES (:utilizador_id, :imovel_id)');
		$statement->execute([
			'utilizador_id' => $favorito->utilizadorId,
			'imovel_id' => $favorito->imovelId,
		]);

		return $favorito;
	}

	public function delete(int $utilizadorId, int $imovelId): bool
	{
		$statement = $this->connection->prepare('DELETE FROM favoritos WHERE utilizador_id = :utilizador_id AND imovel_id = :imovel_id');
		$statement->execute(['utilizador_id' => $utilizadorId, 'imovel_id' => $imovelId]);

		return $statement->rowCount() > 0;
	}

	private function hydrate(array $row): Favorito
	{
		return new Favorito((int) $row['utilizador_id'], (int) $row['imovel_id']);
	}
}