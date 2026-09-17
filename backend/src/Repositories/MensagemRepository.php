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
		$statement = $this->connection->query('SELECT id, remetente_id, destinatario_id, imovel_id, conteudo, lida, enviada_em FROM mensagens ORDER BY id');

		return array_map(fn (array $row): Mensagem => $this->hydrate($row), $statement->fetchAll());
	}

	public function findById(int $id): ?Mensagem
	{
		$statement = $this->connection->prepare('SELECT id, remetente_id, destinatario_id, imovel_id, conteudo, lida, enviada_em FROM mensagens WHERE id = :id LIMIT 1');
		$statement->execute(['id' => $id]);
		$row = $statement->fetch();

		return $row === false ? null : $this->hydrate($row);
	}

	public function create(Mensagem $mensagem): Mensagem
	{
		$statement = $this->connection->prepare('INSERT INTO mensagens (remetente_id, destinatario_id, imovel_id, conteudo, lida) VALUES (:remetente_id, :destinatario_id, :imovel_id, :conteudo, :lida)');
		$statement->execute($this->parameters($mensagem));
		$mensagem->id = (int) $this->connection->lastInsertId();
		$mensagem->enviadaEm = date('Y-m-d H:i:s');

		return $mensagem;
	}

	public function update(Mensagem $mensagem): Mensagem
	{
		if ($mensagem->id === null) {
			throw new InvalidArgumentException('A mensagem precisa de um id para ser atualizada');
		}

		$parameters = $this->parameters($mensagem);
		$parameters['id'] = $mensagem->id;
		$statement = $this->connection->prepare('UPDATE mensagens SET remetente_id = :remetente_id, destinatario_id = :destinatario_id, imovel_id = :imovel_id, conteudo = :conteudo, lida = :lida WHERE id = :id');
		$statement->execute($parameters);

		if ($statement->rowCount() === 0 && $this->findById($mensagem->id) === null) {
			throw new RuntimeException('Mensagem nao encontrada');
		}

		return $mensagem;
	}

	public function delete(int $id): bool
	{
		$statement = $this->connection->prepare('DELETE FROM mensagens WHERE id = :id');
		$statement->execute(['id' => $id]);

		return $statement->rowCount() > 0;
	}

	private function parameters(Mensagem $mensagem): array
	{
		return [
			'remetente_id' => $mensagem->remetenteId,
			'destinatario_id' => $mensagem->destinatarioId,
			'imovel_id' => $mensagem->imovelId,
			'conteudo' => $mensagem->conteudo,
			'lida' => (int) $mensagem->lida,
		];
	}

	private function hydrate(array $row): Mensagem
	{
		return new Mensagem(
			(int) $row['remetente_id'],
			(int) $row['destinatario_id'],
			$row['imovel_id'] === null ? null : (int) $row['imovel_id'],
			(string) $row['conteudo'],
			(bool) $row['lida'],
			(int) $row['id'],
			$row['enviada_em'] === null ? null : (string) $row['enviada_em']
		);
	}
}
