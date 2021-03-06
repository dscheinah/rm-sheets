<?php

use App\Container\Provider;
use Sx\Container\Injector;
use Sx\Message\ServerRequestFactory;
use Sx\Message\StreamFactory;
use Sx\Message\UriFactory;
use Sx\Server\ApplicationInterface;

$baseDirectory = dirname(__DIR__);
// Activate composer auto-loading.
require $baseDirectory . '/vendor/autoload.php';

// Load configuration from the config dir. All files are merged in alphabetical order.
$options = [];
foreach (glob($baseDirectory . '/config/*.php') as $file) {
    $options[] = include $file;
}
$options = array_merge([], ...$options);

// Turn on error reporting if not in production environment according to configuration.
if (($options['env'] ?? '') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Create the injection dependency container and fill it with definitions provided by the application.
$injector = new Injector($options);
$injector->setup(new Provider());

// Create the server request to be handled by the application.
$uriFactory = new UriFactory();
$requestFactory = new ServerRequestFactory($uriFactory);
$streamFactory = new StreamFactory();
$request = $requestFactory
	->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER)
	->withBody($streamFactory->createStreamFromFile('php://input'));

/** @var Sx\Server\ApplicationInterface $app */
$app = $injector->get(ApplicationInterface::class);
// Finally run the application. The app and all middleware are loaded by the injector.
// The middleware chain and routing options are provided with the used factories of the App\Container\Provider.
$app->run($request);
