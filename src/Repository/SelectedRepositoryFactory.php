<?php

namespace App\Repository;

use App\Storage\SelectedStorage;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;

class SelectedRepositoryFactory implements FactoryInterface
{
	public function create(Injector $injector, array $options, string $class): SelectedRepository
	{
		$dir = $options['output_dir'] ?? '';
		if (!$dir || !is_dir($dir)) {
			throw new RuntimeException('The config "output_dir" must point to a directory.');
		}
		return new SelectedRepository(
			$injector->get(SelectedStorage::class),
			$injector->get(StreamFactoryInterface::class),
			$dir
		);
	}
}
