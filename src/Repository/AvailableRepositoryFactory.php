<?php

namespace App\Repository;

use RuntimeException;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;

class AvailableRepositoryFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): AvailableRepository
	{
		return new AvailableRepository($injector->get(AvailableDirectoryProvider::class));
	}
}
