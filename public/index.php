<?php

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;

use Psr\Http\Message\ServerRequestInterface;

use Aura\Router\RouterContainer;

use Framework\Http\ResponseSender;
use Framework\Http\Router\Router;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Application;

use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;


use App\Http\Actions\HomeAction;
use App\Http\Actions\AboutAction;
use App\Http\Actions\Blog;
use App\Http\Actions\ProfileAction;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\CatchExceptionMiddleware;


chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';
require_once 'src/App/helpers/funcs.php';

// Middleware

$params = [
  'users' => [
    'admin' => '123'
  ],
  'debug' => true
];

$authMiddleWare = new AuthMiddleware($params['users']);
$timerMiddleware = new TimerMiddleware();

// Initialization
$request = ServerRequestFactory::fromGlobals();
$resolver = new MiddlewareResolver();
$app = new Application($resolver,  new NotFoundHandler());

// Routing
$aura = new RouterContainer();
$map = $aura->getMap();
$router = new AuraRouterAdapter($aura);

$map->get('home', '/', HomeAction::class);
$map->get('about', '/about', AboutAction::class);
$map->get('blog', '/blog', Blog\IndexAction::class);
$map->get('profile', '/profile', [
  new AuthMiddleware($params['users']),
  new ProfileAction()
]);
$map->get('blog_show', '/blog/{id}', new Blog\ShowAction())->tokens(['id' => '\d+']);

$app->pipe(new CatchExceptionMiddleware($params['debug']));
$app->pipe(TimerMiddleware::class);

$app->pipe(new RouteMiddleware($router, $resolver)); // Определяем маршрут
$app->pipe(new DispatchMiddleware($resolver)); // Выполняем маршрут


// Running //
$response = $app->run($request);
$emitter = new ResponseSender();
$emitter->emit($response);