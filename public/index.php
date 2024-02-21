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
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;

use App\Http\Actions\HomeAction;
use App\Http\Actions\AboutAction;
use App\Http\Actions\Blog;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';


// Initialization
$request = ServerRequestFactory::fromGlobals();
$resolver = new ActionResolver();


// Routing
$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', new HomeAction());
$map->get('about', '/about', new AboutAction());
$map->get('blog', '/blog', new Blog\IndexAction());
$map->get('blog_show', '/blog/{id}', new Blog\ShowAction())->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);

try{
  $result = $router->match($request);

  foreach($result->getAttributes() as $attribute => $value){
    $request = $request->withAttribute($attribute, $value);
  }


  $action = $resolver->resolve($result->getHandler());
  $response = $action($request);

}catch (RequestNotMatchedException $e){
  $response = new JsonResponse(['error' => 'Undefined Page'], 404);
}

$emitter = new ResponseSender();
$emitter->emit($response);