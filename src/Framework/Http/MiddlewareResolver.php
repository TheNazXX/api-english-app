<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Pipeline\Pipeline;

class MiddlewareResolver
{
  public function resolve($handler): callable
  {
    if(is_array($handler)){
      return $this->createPipe($handler);
    };


    if(\is_string($handler)){
      return function(ServerRequestInterface $request, callable $next) use ($handler){
        return (new $handler())($request, $next);
      };
    };

    return $handler;
  }

  public function createPipe(array $handlers){
    $pipeline = new Pipeline();

    foreach($handlers as $middleware){
      $pipeline->pipe($this->resolve($middleware));
    }

    return $pipeline;
  }
}