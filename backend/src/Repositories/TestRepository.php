<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class TestRepository
{
	public function __construct(private readonly PdoConnectionInterface $pdoProvider)
	{
	}

	public function getPdoProvider(): PdoConnectionInterface
	{
		return $this->pdoProvider;
	}
}
