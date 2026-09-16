<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/autoload.php';
require_once dirname(__DIR__) . '/src/Core/Container.php';

final class FakePdoProvider implements PdoConnectionInterface
{
	public function getConnection(): PDO
	{
		throw new LogicException('A ligacao PDO nao deve ser aberta neste teste');
	}
}

$container = new Container();
$provider = new FakePdoProvider();
$container->bind(PdoConnectionInterface::class, static function () use ($provider): FakePdoProvider {
	return $provider;
});

$service = $container->get(TestService::class);
$repository = $service->getRepository();

if ($service !== $container->get(TestService::class)) {
	throw new RuntimeException('O Service nao foi mantido como singleton');
}
if (!$repository instanceof TestRepository) {
	throw new RuntimeException('O Repository nao foi resolvido');
}
if ($repository->getPdoProvider() !== $provider) {
	throw new RuntimeException('O contrato PDO nao foi injetado');
}

echo "ContainerTest: PASS\n";
