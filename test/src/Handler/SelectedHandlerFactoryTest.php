<?php

namespace AppTest\Handler;

use App\Handler\SelectedHandler;
use App\Handler\SelectedHandlerFactory;
use App\Repository\SelectedRepository;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class SelectedHandlerFactoryTest extends TestCase
{
	public function testCreate(): void
	{
		$injector = new Injector();
		$injector->set(ResponseHelperInterface::class, ResponseHelper::class);
		$injector->set(SelectedRepository::class, $this->createMock(SelectedRepository::class));
		$factory = new SelectedHandlerFactory();
		$factory->create($injector, [], SelectedHandler::class);
		self::assertTrue(true);
	}
}
