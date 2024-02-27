<?php

namespace Framework\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

use Framework\Http\Router\Result;
use Framework\Http\Router\AuraRouterAdapter;

use App\Http\Middleware\NotFoundHandler;

class RouteMiddleware
{
  private $router;

  public function __construct(AuraRouterAdapter $router){
    $this->router = $router;
  }

  public function __invoke(ServerRequestInterface $request, callable $next){
    try{
      $result = $this->router->match($request);

      foreach($result->getAttributes() as $attribute => $value){
        $request = $request->witAttribute($attribute, $value);
      };

      return $next($request->withAttribute(Result::class, $result));

    }catch(RequestNotMatchedException $e){
      return $next($request);
    }
  }
}