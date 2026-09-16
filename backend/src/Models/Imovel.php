<?php

declare(strict_types=1);

/**
 * Entidade Imovel
 * Responsavel: Pessoa 3
 */
class Imovel
{
	public ?int $id;
	public int $proprietarioId;
	public string $titulo;
	public ?string $descricao;
	public string $tipo;
	public ?string $tipologia;
	public float $preco;
	public string $localizacao;
	public int $quartos;
	public bool $verificado;
	public bool $disponivel;
	public ?string $criadoEm;

	public function __construct(
		int $proprietarioId = 0,
		string $titulo = '',
		?string $descricao = null,
		string $tipo = 'casa',
		?string $tipologia = null,
		float $preco = 0.0,
		string $localizacao = '',
		int $quartos = 0,
		bool $verificado = false,
		bool $disponivel = true,
		?int $id = null,
		?string $criadoEm = null
	) {
		$this->id = $id;
		$this->proprietarioId = $proprietarioId;
		$this->titulo = $titulo;
		$this->descricao = $descricao;
		$this->tipo = $tipo;
		$this->tipologia = $tipologia;
		$this->preco = $preco;
		$this->localizacao = $localizacao;
		$this->quartos = $quartos;
		$this->verificado = $verificado;
		$this->disponivel = $disponivel;
		$this->criadoEm = $criadoEm;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'proprietario_id' => $this->proprietarioId,
			'titulo' => $this->titulo,
			'descricao' => $this->descricao,
			'tipo' => $this->tipo,
			'tipologia' => $this->tipologia,
			'preco' => $this->preco,
			'localizacao' => $this->localizacao,
			'quartos' => $this->quartos,
			'verificado' => $this->verificado,
			'disponivel' => $this->disponivel,
			'criado_em' => $this->criadoEm,
		];
	}
}
