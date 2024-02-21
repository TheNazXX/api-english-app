<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

use Framework\Http\Router\Router;
use Framework\Http\Router\Exception\RequestNotMatchedException;

class AuraRouterAdapter implements RouterInterface
{
  private $aura;

  public function __construct(\Aura\Router\RouterContainer $aura){
    $this->aura = $aura;
  }

  public function match(ServerRequestInterface $request): Result{
    $matcher = $this->aura->getMatcher();

    if($route = $matcher->match($request)){
      return new Result($route->name, $route->handler, $route->attributes);
    }

    throw new RequestNotMatchedException($request);
  }

  public function generate($name, array $params = []): string {
    $generator = $this->aura->getGenerator();

    try{
      return $generator->generate($name, $params);
    }catch (RouteNotFound $e){
      throw new RouteNotFoundException($name, $params, $e);
    }
  }

}