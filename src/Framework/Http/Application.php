<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface; 

use Framework\Http\Pipeline\Pipeline;
use Framework\Http\MiddlewareResolver;



class Application extends Pipeline{
  private $resolver;
  private $default;

  public function __construct(MiddlewareResolver $resolver, callable $default){
    parent::__construct();
    $this->resolver = $resolver;
    $this->default = $default;
  }

  public function pipe($middleware){
    parent::pipe($this->resolver->resolve($middleware));
  }

  public function run(ServerRequestInterface $request, ResponseInterface $response){
    return $this($request, $response, $this->default);
  }
}