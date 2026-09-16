<?php

declare(strict_types=1);

interface PdoConnectionInterface
{
	public function getConnection(): PDO;
}
