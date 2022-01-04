<?php

namespace App\Container;

use App\ApplicationFactory;
use App\Handler\AvailableHandler;
use App\Handler\AvailableHandlerFactory;
use App\Handler\ListHandler;
use App\Handler\ListHandlerFactory;
use App\Handler\SaveHandler;
use App\Handler\SaveHandlerFactory;
use App\Handler\SelectedHandler;
use App\Handler\SelectedHandlerFactory;
use App\Repository\AvailableDirectoryProvider;
use App\Repository\AvailableDirectoryProviderFactory;
use App\Storage\SelectedStorage;
use App\Repository\AvailableRepository;
use App\Repository\AvailableRepositoryFactory;
use App\Repository\SelectedRepository;
use App\Repository\SelectedRepositoryFactory;
use App\RouterFactory;
use Sx\Application\Container\ApplicationProvider;
use Sx\Container\Injector;
use Sx\Container\ProviderInterface;
use Sx\Data\Backend\MySqlBackendFactory;
use Sx\Data\BackendInterface;
use Sx\Data\StorageFactory;
use Sx\Log\Container\LogProvider;
use Sx\Message\Container\MessageProvider;
use Sx\Server\ApplicationInterface;
use Sx\Server\Container\ServerProvider;
use Sx\Server\RouterInterface;

/**
 * This class is used in index.php to setup the dependency injector.
 */
class Provider implements ProviderInterface
{
    /**
     * Adds all used mappings for interfaces and classes to factories.
     *
     * @param Injector $injector
     */
    public function provide(Injector $injector): void
    {
        // First do a setup of all modules installed by composer.
        $injector->setup(new ApplicationProvider());
        $injector->setup(new LogProvider());
        $injector->setup(new MessageProvider());
        $injector->setup(new ServerProvider());
        // Add all local classes and factories.
		$injector->set(ApplicationInterface::class, ApplicationFactory::class);
        $injector->set(RouterInterface::class, RouterFactory::class);
		$injector->set(BackendInterface::class, MySqlBackendFactory::class);
		$injector->set(SelectedStorage::class, StorageFactory::class);
		$injector->set(ListHandler::class, ListHandlerFactory::class);
		$injector->set(AvailableHandler::class, AvailableHandlerFactory::class);
		$injector->set(SelectedHandler::class, SelectedHandlerFactory::class);
		$injector->set(SaveHandler::class, SaveHandlerFactory::class);
		$injector->set(AvailableRepository::class, AvailableRepositoryFactory::class);
		$injector->set(AvailableDirectoryProvider::class, AvailableDirectoryProviderFactory::class);
		$injector->set(SelectedRepository::class, SelectedRepositoryFactory::class);
    }
}
