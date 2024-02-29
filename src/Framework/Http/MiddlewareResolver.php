<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

use Framework\Http\Pipeline\PsrHandlerWrapper;
use Framework\Http\Pipeline\UnknownMiddleware;

use Framework\Http\Pipeline\Pipeline;

class MiddlewareResolver
{
  public function resolve($handler): callable
  {
    if(\is_array($handler)){
      return $this->createPipe($handler);
    };

    if(\is_string($handler)){

      return function(ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
        $middleware = $this->resolve(new $handler());
        return $middleware($request, $response, $next);
      };
    };

    if($handler instanceof MiddlewareInterface){

      return function(ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
        return $handler->process($request, new PsrHandlerWrapper($next));
      };
    }

    if(\is_object($handler)){

      $reflection = new \ReflectionObject($handler);
      if($reflection->hasMethod('__invoke')){

        $method = $reflection->getMethod('__invoke');
        $parameters = $method->getParameters();
        
        if(count($parameters) === 2 && $parameters[1]->getType()->getName() === 'callable'){

          return function(ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
            return $handler($request, $next);
          };
        };

        return $handler;
      }
    }

    throw new UnknownMiddleware($handler);
  }

  public function createPipe(array $handlers){
    $pipeline = new Pipeline();

    foreach($handlers as $middleware){
      $pipeline->pipe($this->resolve($middleware));
    }

    return $pipeline;
  }
}