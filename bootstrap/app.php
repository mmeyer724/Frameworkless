<?php
use FastRoute\Dispatcher;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once __DIR__ . '/../vendor/autoload.php';


/**
 * Dotenv setup
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();


/**
 * Error handler
 */
$whoops = new Run;
if (getenv('MODE') === 'dev') {
    $whoops->pushHandler(new PrettyPageHandler);
} else {
    $whoops->pushHandler(function () {
        Response::create('Uh oh, something broke internally.', Response::HTTP_INTERNAL_SERVER_ERROR)->send();
    });
}
$whoops->register();


/**
 * Container setup
 */
$container = new Container();
$container->add('Twig_Environment')
    ->withArgument(new Twig_Loader_Filesystem(__DIR__ . '/../views/'));
$container->delegate(
    new ReflectionContainer() // Auto-wiring
);


/**
 * Routes
 */
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $routes = require __DIR__ . '/routes.php';
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});


/**
 * Dispatch
 */
$request = Request::createFromGlobals();
$route_info = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
switch ($route_info[0]) {
    case Dispatcher::NOT_FOUND:
        Response::create("404 Not Found", Response::HTTP_NOT_FOUND)->send();
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        Response::create("405 Method Not Allowed", Response::HTTP_METHOD_NOT_ALLOWED)->send();
        break;
    case Dispatcher::FOUND:
        $class_name = $route_info[1][0];
        $method = $route_info[1][1];
        $vars = $route_info[2];
        $object = $container->get($class_name);

        $response = $object->$method($vars);
        if ($response instanceof Response) {
            $response->prepare(Request::createFromGlobals());
            $response->send();
        }
        break;
}
