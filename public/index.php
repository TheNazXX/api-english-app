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

use App\Http\Actions\HomeAction;
use App\Http\Actions\AboutAction;
use App\Http\Actions\Blog;
use App\Http\Actions\ProfileAction;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\NotFoundHandler;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';
require_once 'src/App/helpers/funcs.php';

// Middleware

$params = [
  'users' => [
    'admin' => '123'
  ]
];

$authMiddleWare = new AuthMiddleware($params['users']);
$timerMiddleware = new TimerMiddleware();

// Initialization
$request = ServerRequestFactory::fromGlobals();

// Routing
$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', HomeAction::class);
$map->get('about', '/about', AboutAction::class);
$map->get('blog', '/blog', Blog\IndexAction::class);


$map->get('profile', '/profile', [
  new AuthMiddleware($params['users']),
  ProfileAction::class
]);


$map->get('blog_show', '/blog/{id}', new Blog\ShowAction())->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$pipeline  = new Pipeline();

$pipeline->pipe($resolver->resolve(TimerMiddleware::class));

try{
  $result = $router->match($request);
  
  foreach($result->getAttributes() as $attribute => $value){
    $request = $request->withAttribute($attribute, $value);
  }
  
  $handlers = $result->getHandler(); // Массив actions;

  foreach(is_array($handlers) ? $handlers : [$handlers] as $handler){
    $pipeline->pipe($resolver->resolve($handler));
  }
  
  $response = $pipeline($request, new NotFoundHandler());

}catch (RequestNotMatchedException $e){}

$response = $pipeline($request, new NotFoundHandler());

$emitter = new ResponseSender();
$emitter->emit($response);