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
		$statement = $this->connection->query('SELECT id, imovel_id, interessado_id, data_visita, estado, criado_em FROM visitas ORDER BY data_visita, id');

		return array_map(fn (array $row): Visita => $this->hydrate($row), $statement->fetchAll());
	}

	public function findById(int $id): ?Visita
	{
		$statement = $this->connection->prepare('SELECT id, imovel_id, interessado_id, data_visita, estado, criado_em FROM visitas WHERE id = :id LIMIT 1');
		$statement->execute(['id' => $id]);
		$row = $statement->fetch();

		return $row === false ? null : $this->hydrate($row);
	}

	public function create(Visita $visita): Visita
	{
		$statement = $this->connection->prepare('INSERT INTO visitas (imovel_id, interessado_id, data_visita, estado) VALUES (:imovel_id, :interessado_id, :data_visita, :estado)');
		$statement->execute($this->parameters($visita));
		$visita->id = (int) $this->connection->lastInsertId();
		$visita->criadoEm = date('Y-m-d H:i:s');

		return $visita;
	}

	public function update(Visita $visita): Visita
	{
		if ($visita->id === null) {
			throw new InvalidArgumentException('A visita precisa de um id para ser atualizada');
		}

		$parameters = $this->parameters($visita);
		$parameters['id'] = $visita->id;
		$statement = $this->connection->prepare('UPDATE visitas SET imovel_id = :imovel_id, interessado_id = :interessado_id, data_visita = :data_visita, estado = :estado WHERE id = :id');
		$statement->execute($parameters);

		if ($statement->rowCount() === 0 && $this->findById($visita->id) === null) {
			throw new RuntimeException('Visita nao encontrada');
		}

		return $visita;
	}

	public function delete(int $id): bool
	{
		$statement = $this->connection->prepare('DELETE FROM visitas WHERE id = :id');
		$statement->execute(['id' => $id]);

		return $statement->rowCount() > 0;
	}

	private function parameters(Visita $visita): array
	{
		return [
			'imovel_id' => $visita->imovelId,
			'interessado_id' => $visita->interessadoId,
			'data_visita' => $visita->dataVisita,
			'estado' => $visita->estado,
		];
	}

	private function hydrate(array $row): Visita
	{
		return new Visita(
			(int) $row['imovel_id'],
			(int) $row['interessado_id'],
			(string) $row['data_visita'],
			(string) $row['estado'],
			(int) $row['id'],
			$row['criado_em'] === null ? null : (string) $row['criado_em']
		);
	}
}
