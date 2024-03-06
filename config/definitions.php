<?php 

use Framework\Container\Container;

use Laminas\Diactoros\Response;
use Aura\Router\RouterContainer;

use Framework\Http\Router\Router;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Application;

use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\CatchExceptionMiddleware;


$container->set(Application::class, function(Container $container){
  return new Application(
    $container->get(MiddlewareResolver::class), 
    $container->get(Router::class),
    $container->get(NotFoundHandler::class)
  );
});


$container->set(NotFoundHandler::class, function(){
  return new NotFoundHandler();
});

$container->set(AuthMiddleware::class, function(Container $container){
  return new AuthMiddleware($container->get('config')['users'], new Response());
});



$container->set(CatchExceptionMiddleware::class, function(Container $container){
  return new CatchExceptionMiddleware($container->get('config')['debug']);
});

$container->set(TimerMiddleware::class, function(){
  return new TimerMiddleware();
});

$container->set(MiddlewareResolver::class, function(){
  return new MiddlewareResolver();
});


$container->set(RouteMiddleware::class, function(Container $container){
  return new RouteMiddleware($container->get(Router::class));
});


$container->set(DispatchMiddleware::class, function(Container $container){
  return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(Router::class, function(Container $container){
  return new AuraRouterAdapter(new RouterContainer());
});