<?php 

namespace Framework\Http\Router;

use Framework\Http\Router\Route;

class RouteCollection
{
  public $map = [];

  public function addRoute(Route $route)
  {
    $this->map[] = $route;
  }

  public function any($name, $pattern, $handler, array $tokens = []): void
  {
    $this->addRoute(new Route($name, $pattern, $handler, [],  $tokens));
  }

  public function get($name, $pattern, $handler, array $tokens = []): void
  {
    $this->addRoute(new Route($name, $pattern, $handler, ['GET'],  $tokens));
  }

  public function post($name, $pattern, $handler, array $tokens = []): void
  {
    $this->addRoute(new Route($name, $pattern, $handler, ['POST'],  $tokens));
  }



  public function getMap()
  {
    return $this->map;
  }
}