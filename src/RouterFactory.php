<?php

namespace App;

use App\Handler\AvailableHandler;
use App\Handler\ListHandler;
use App\Handler\SaveHandler;
use App\Handler\SelectedHandler;
use Sx\Container\FactoryInterface;
use Sx\Container\Injector;
use Sx\Server\MiddlewareHandlerInterface;
use Sx\Server\Router;

/**
 * The factory for the router. It defines all available routes.
 */
class RouterFactory implements FactoryInterface
{
	/**
	 * Creates the router and registers all handlers for routes.
	 *
	 * @param Injector $injector
	 * @param array    $options
	 * @param string   $class
	 *
	 * @return Router
	 */
    public function create(Injector $injector, array $options, string $class): Router
    {
        // The prefix can be set in the config if the index.php is not available from "/".
        $prefix = $options['prefix'] ?? '';
        $router = new Router($injector->get(MiddlewareHandlerInterface::class));
        // Add the example handler for the backend page.
        $router->post($prefix . 'list', ListHandler::class);
		$router->get($prefix . 'available', AvailableHandler::class);
		$router->get($prefix . 'selected', SelectedHandler::class);
		$router->post($prefix . 'selected', SaveHandler::class);
        return $router;
    }
}
