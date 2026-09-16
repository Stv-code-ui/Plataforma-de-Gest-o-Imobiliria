<?php

declare(strict_types=1);

require_once __DIR__ . '/Contracts/ContainerInterface.php';

final class Container implements ContainerInterface
{
	private array $bindings = [];
	private array $instances = [];

	public function bind(string $abstract, string|callable $concrete, bool $singleton = true): void
	{
		$this->bindings[$abstract] = [
			'concrete' => $concrete,
			'singleton' => $singleton,
		];
	}

	public function get(string $abstract): object
	{
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		$binding = $this->bindings[$abstract] ?? null;
		$concrete = $binding['concrete'] ?? $abstract;
		$instance = is_callable($concrete) && !is_string($concrete)
			? $concrete($this)
			: $this->build((string) $concrete);

		if (!is_object($instance)) {
			throw new LogicException(sprintf('A dependencia [%s] deve devolver um objeto', $abstract));
		}

		if (($binding['singleton'] ?? true) === true) {
			$this->instances[$abstract] = $instance;
		}

		return $instance;
	}

	private function build(string $className): object
	{
		if (!class_exists($className)) {
			throw new LogicException(sprintf('A classe [%s] nao existe', $className));
		}

		$reflection = new ReflectionClass($className);
		if (!$reflection->isInstantiable()) {
			throw new LogicException(sprintf('A classe [%s] nao pode ser instanciada', $className));
		}

		$constructor = $reflection->getConstructor();
		if ($constructor === null) {
			return $reflection->newInstance();
		}

		$arguments = [];
		foreach ($constructor->getParameters() as $parameter) {
			$type = $parameter->getType();
			if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
				if ($parameter->isDefaultValueAvailable()) {
					$arguments[] = $parameter->getDefaultValue();
					continue;
				}

				throw new LogicException(sprintf(
					'A dependencia [%s] de [%s] nao pode ser resolvida automaticamente',
					$parameter->getName(),
					$className
				));
			}

			$arguments[] = $this->get($type->getName());
		}

		return $reflection->newInstanceArgs($arguments);
	}
}
