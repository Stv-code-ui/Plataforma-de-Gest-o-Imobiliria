<?php

declare(strict_types=1);

class ImovelFoto
{
    public ?int $id;
    public int $imovelId;
    public string $url;

    public function __construct(int $imovelId = 0, string $url = '', ?int $id = null)
    {
        $this->id = $id;
        $this->imovelId = $imovelId;
        $this->url = $url;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'imovel_id' => $this->imovelId,
            'url' => $this->url,
        ];
    }
}