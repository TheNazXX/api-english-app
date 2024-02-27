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
$resolver = new MiddlewareResolver();
$app = new Application($resolver);

// Routing
$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', HomeAction::class);
$map->get('about', '/about', AboutAction::class);
$map->get('blog', '/blog', Blog\IndexAction::class);


$map->get('profile', '/profile', [
  new AuthMiddleware($params['users']),
  new ProfileAction()
]);


$map->get('blog_show', '/blog/{id}', new Blog\ShowAction())->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);
$pipeline  = new Pipeline();

$app->pipe(TimerMiddleware::class);

try{
  $result = $router->match($request);

  foreach($result->getAttributes() as $attribute => $value){
    $request = $request->withAttribute($attribute, $value);
  }
  
  $handler = $result->getHandler(); // Массив actions;
 
  $app->pipe($handler);

}catch (RequestNotMatchedException $e){}

$response = $app($request, new NotFoundHandler());

$emitter = new ResponseSender();
$emitter->emit($response);