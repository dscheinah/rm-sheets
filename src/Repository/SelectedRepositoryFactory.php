<?php

namespace App\Repository;

use App\Storage\SelectedStorage;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;

class SelectedRepositoryFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): SelectedRepository
	{
		return new SelectedRepository($injector->get(SelectedStorage::class));
	}
}
