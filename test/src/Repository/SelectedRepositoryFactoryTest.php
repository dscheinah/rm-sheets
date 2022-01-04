<?php

namespace AppTest\Repository;

use App\Repository\SelectedRepository;
use App\Repository\SelectedRepositoryFactory;
use App\Storage\SelectedStorage;
use PHPUnit\Framework\TestCase;
use Sx\Container\Injector;

class SelectedRepositoryFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(SelectedStorage::class, $this->createMock(SelectedStorage::class));
		$factory = new SelectedRepositoryFactory();
		$factory->create($injector, [], SelectedRepository::class);
		self::assertTrue(true);
	}
}
