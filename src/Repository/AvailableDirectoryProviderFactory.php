<?php

namespace App\Repository;

use RuntimeException;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;

class AvailableDirectoryProviderFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): AvailableDirectoryProvider
	{
		$dir = $options['data_dir'] ?? '';
		if (!$dir || !is_readable($dir) || !is_dir($dir)) {
			throw new RuntimeException('The config "data_dir" must be set and a be readable directory.');
		}
		return new AvailableDirectoryProvider($dir);
	}
}
