<?php

namespace App\Handler;

use App\Repository\AvailableRepository;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class AvailableHandlerFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): AvailableHandler
	{
		return new AvailableHandler(
			$injector->get(ResponseHelperInterface::class),
			$injector->get(AvailableRepository::class)
		);
	}
}
