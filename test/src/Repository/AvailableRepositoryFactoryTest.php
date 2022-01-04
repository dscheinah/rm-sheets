<?php

namespace AppTest\Repository;

use App\Repository\AvailableDirectoryProvider;
use App\Repository\AvailableRepository;
use App\Repository\AvailableRepositoryFactory;
use PHPUnit\Framework\TestCase;
use Sx\Container\Injector;

class AvailableRepositoryFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(AvailableDirectoryProvider::class, $this->createMock(AvailableDirectoryProvider::class));
		$factory = new AvailableRepositoryFactory();
		$factory->create($injector, [], AvailableRepository::class);
		self::assertTrue(true);
	}
}
