<?php

declare(strict_types=1);

class Mensagem
{
	public ?int $id;
	public int $remetenteId;
	public int $destinatarioId;
	public ?int $imovelId;
	public string $conteudo;
	public bool $lida;
	public ?string $enviadaEm;

	public function __construct(
		int $remetenteId = 0,
		int $destinatarioId = 0,
		?int $imovelId = null,
		string $conteudo = '',
		bool $lida = false,
		?int $id = null,
		?string $enviadaEm = null
	) {
		$this->id = $id;
		$this->remetenteId = $remetenteId;
		$this->destinatarioId = $destinatarioId;
		$this->imovelId = $imovelId;
		$this->conteudo = $conteudo;
		$this->lida = $lida;
		$this->enviadaEm = $enviadaEm;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'remetente_id' => $this->remetenteId,
			'destinatario_id' => $this->destinatarioId,
			'imovel_id' => $this->imovelId,
			'conteudo' => $this->conteudo,
			'lida' => $this->lida,
			'enviada_em' => $this->enviadaEm,
		];
	}
}
