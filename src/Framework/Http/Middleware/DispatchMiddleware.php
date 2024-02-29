<?php

namespace Framework\Http\Middleware;

use Framework\Http\MiddlewareResolver;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;


class DispatchMiddleware
{
  private $resolver;

  public function __construct(MiddlewareResolver $resolver){
    $this->resolver = $resolver;
  }

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next){
  
    if(!$result = $request->getAttribute(Result::class)){
      return $next($request);
    }
    
    $middleware = $this->resolver->resolve($result->getHandler());
    return $middleware($request, $response, $next);
  }
}