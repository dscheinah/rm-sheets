<?php

namespace App\Handler;

use App\Repository\SelectedRepository;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;
use Sx\Message\Response\ResponseHelperInterface;

class SelectedHandlerFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): SelectedHandler
	{
		return new SelectedHandler(
			$injector->get(ResponseHelperInterface::class),
			$injector->get(SelectedRepository::class)
		);
	}
}
