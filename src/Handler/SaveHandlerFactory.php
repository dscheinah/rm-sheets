<?php

namespace App\Handler;

use App\Repository\SelectedRepository;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class SaveHandlerFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): SaveHandler
	{
		return new SaveHandler(
			$injector->get(ResponseHelperInterface::class),
			$injector->get(SelectedRepository::class)
		);
	}
}
