<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Mensagem.php';
require_once dirname(__DIR__) . '/Repositories/MensagemRepository.php';

class MensagemService
{
	public function __construct(private readonly MensagemRepository $repository)
	{
	}

	public function list(): array
	{
		return $this->repository->findAll();
	}

	public function find(int $id): ?Mensagem
	{
		return $this->repository->findById($id);
	}

	public function create(Mensagem $mensagem): Mensagem
	{
		return $this->repository->create($mensagem);
	}

	public function update(Mensagem $mensagem): Mensagem
	{
		return $this->repository->update($mensagem);
	}

	public function delete(int $id): bool
	{
		return $this->repository->delete($id);
	}
}
