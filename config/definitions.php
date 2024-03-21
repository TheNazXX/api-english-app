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
use App\DB;

use App\Http\Actions\HomeAction;

return [
  Application::class => function(Container $container){
    return new Application(
      $container->get(MiddlewareResolver::class), 
      $container->get(Router::class),
      $container->get(NotFoundHandler::class)
    );
  },
  CatchExceptionMiddleware::class => function(Container $container){
    return new CatchExceptionMiddleware($container->get('config')['debug']);
  },
  AuthMiddleware::class => function(Container $container){
    return new AuthMiddleware($container->get('config')['users'], new Response());
  },
  MiddlewareResolver::class => function(Container $container){
    return new MiddlewareResolver($container);
  },
  Router::class => function(Container $container){ // !!! (Имя - интерфейс, контейнер не обеспечит автоматически класс этой зависимостью т.к он работает только с классами)
    return new AuraRouterAdapter(new RouterContainer());
  },
  RouteMiddleware::class => function(Container $container){ // !!!
    return new RouteMiddleware($container->get(Router::class));
  },
  DB::class => function(Container $container){
    return DB::getInstance()->getConnection($container->get('config')['db_config']);
  }
];