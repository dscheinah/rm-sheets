<?php

namespace AppTest\Repository;

use App\Repository\SelectedRepository;
use App\Repository\SelectedRepositoryFactory;
use App\Storage\SelectedStorage;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use Sx\Container\Injector;

class SelectedRepositoryFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(SelectedStorage::class, $this->createMock(SelectedStorage::class));
		$injector->set(StreamFactoryInterface::class, $this->createMock(StreamFactoryInterface::class));
		$factory = new SelectedRepositoryFactory();
		$factory->create($injector, ['output_dir' => sys_get_temp_dir()], SelectedRepository::class);
		self::assertTrue(true);
		$this->expectException(RuntimeException::class);
		$factory->create($injector, [], SelectedRepository::class);
	}
}
