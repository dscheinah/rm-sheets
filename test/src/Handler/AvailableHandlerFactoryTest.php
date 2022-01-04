<?php

namespace AppTest\Handler;

use App\Handler\AvailableHandler;
use App\Handler\AvailableHandlerFactory;
use App\Repository\AvailableRepository;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class AvailableHandlerFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(ResponseHelperInterface::class, ResponseHelper::class);
		$injector->set(AvailableRepository::class, $this->createMock(AvailableRepository::class));
		$factory = new AvailableHandlerFactory();
		$factory->create($injector, [], AvailableHandler::class);
		self::assertTrue(true);
	}
}
