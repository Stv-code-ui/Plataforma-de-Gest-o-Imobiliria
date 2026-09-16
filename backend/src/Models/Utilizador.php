<?php

declare(strict_types=1);

/**
 * Entidade Utilizador
 * Responsavel: Pessoa 3
 */
class Utilizador
{
	public ?int $id;
	public string $nome;
	public string $email;
	public string $senha;
	public ?string $telefone;
	public string $tipo;
	public ?string $criadoEm;

	public function __construct(
		string $nome = '',
		string $email = '',
		string $senha = '',
		?string $telefone = null,
		string $tipo = 'interessado',
		?int $id = null,
		?string $criadoEm = null
	) {
		$this->id = $id;
		$this->nome = $nome;
		$this->email = $email;
		$this->senha = $senha;
		$this->telefone = $telefone;
		$this->tipo = $tipo;
		$this->criadoEm = $criadoEm;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'nome' => $this->nome,
			'email' => $this->email,
			'senha' => $this->senha,
			'telefone' => $this->telefone,
			'tipo' => $this->tipo,
			'criado_em' => $this->criadoEm,
		];
	}
}
