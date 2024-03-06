<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface; 

use Framework\Http\Pipeline\Pipeline;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\Router;
use Framework\Http\Router\RouteData;



class Application extends Pipeline{
  private $resolver;
  private $default;
  private $router;

  public function __construct(MiddlewareResolver $resolver, Router $router, callable $default){
    parent::__construct();
    $this->resolver = $resolver;
    $this->default = $default;
    $this->router = $router;
  }

  public function pipe($middleware){
    parent::pipe($this->resolver->resolve($middleware));
  }

  public function run(ServerRequestInterface $request, ResponseInterface $response){
    return $this($request, $response, $this->default);
  }

  public function route($name, $path, $handler, array $methods = [], array $options = []){
    $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
  }

  public function any($name, $path, $handler, array $options = []){
    $this->route($name, $path, $handler, [], $options);
  }

  public function get($name, $path, $handler, array $options = []){
    $this->route($name, $path, $handler, ['GET'], $options);
  }

  public function post($name, $path, $handler, array $options = []){
    $this->route($name, $path, $handler, ['POST'], $options);
  }
}