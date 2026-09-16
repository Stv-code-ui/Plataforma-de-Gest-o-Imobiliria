<?php

declare(strict_types=1);

class Favorito
{
    public int $utilizadorId;
    public int $imovelId;

    public function __construct(int $utilizadorId = 0, int $imovelId = 0)
    {
        $this->utilizadorId = $utilizadorId;
        $this->imovelId = $imovelId;
    }

    public function toArray(): array
    {
        return [
            'utilizador_id' => $this->utilizadorId,
            'imovel_id' => $this->imovelId,
        ];
    }
}