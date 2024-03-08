<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

use Framework\Http\Router\Route;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Result;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;


class MyRouter
{

  private $map;

  public function __construct(RouteCollection $map)
  {
    $this->map = $map;
  }

  public function match(ServerRequestInterface $request)
  {
    
    foreach($this->map->getMap() as $route){
      if($result = $route->match($request)){
        return $result;
      };
    }

    throw new RequestNotMatchedException($request);
  }

  public function generate($name, $params = [])
  {

    foreach($this->map->getMap() as $route){
      if(null !== $url = $route->generate($name, array_filter($params))){
        return $url;
      }
    }

    throw new RouteNotFoundException($name, $params);
  }

}