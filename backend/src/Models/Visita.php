<?php

declare(strict_types=1);

/**
 * Entidade Visita
 * Responsavel: Pessoa 3
 */
class Visita
{
	public ?int $id;
	public int $imovelId;
	public int $interessadoId;
	public string $dataVisita;
	public string $estado;
	public ?string $criadoEm;

	public function __construct(
		int $imovelId = 0,
		int $interessadoId = 0,
		string $dataVisita = '',
		string $estado = 'pendente',
		?int $id = null,
		?string $criadoEm = null
	) {
		$this->id = $id;
		$this->imovelId = $imovelId;
		$this->interessadoId = $interessadoId;
		$this->dataVisita = $dataVisita;
		$this->estado = $estado;
		$this->criadoEm = $criadoEm;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'imovel_id' => $this->imovelId,
			'interessado_id' => $this->interessadoId,
			'data_visita' => $this->dataVisita,
			'estado' => $this->estado,
			'criado_em' => $this->criadoEm,
		];
	}
}
