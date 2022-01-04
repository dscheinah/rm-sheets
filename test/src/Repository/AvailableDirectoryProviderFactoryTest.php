<?php

namespace AppTest\Repository;

use App\Repository\AvailableDirectoryProvider;
use App\Repository\AvailableDirectoryProviderFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sx\Container\Injector;

class AvailableDirectoryProviderFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$factory = new AvailableDirectoryProviderFactory();
		$factory->create(new Injector(), ['data_dir' => sys_get_temp_dir()], AvailableDirectoryProvider::class);
		self::assertTrue(true);
		$this->expectException(RuntimeException::class);
		$factory->create(new Injector(), [], AvailableDirectoryProvider::class);
	}
}
