<?php

namespace AppTest\Handler;

use App\Handler\SaveHandler;
use App\Handler\SaveHandlerFactory;
use App\Repository\SelectedRepository;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class SaveHandlerFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(ResponseHelperInterface::class, ResponseHelper::class);
		$injector->set(SelectedRepository::class, $this->createMock(SelectedRepository::class));
		$factory = new SaveHandlerFactory();
		$factory->create($injector, [], SaveHandler::class);
		self::assertTrue(true);
	}
}
