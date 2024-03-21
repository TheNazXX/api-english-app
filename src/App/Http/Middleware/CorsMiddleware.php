<?php

namespace App\Http\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware 
{
  public function __invoke(ServerRequestInterface $request, callable $next)
  {

    $response = $next($request);

    $response = $response
    ->withHeader("Access-Control-Allow-Origin", "http://localhost:3000")
    ->withHeader("Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE, OPTIONS")
    ->withHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");

    if ($request->getMethod() === 'OPTIONS') {
      $response = $response->withStatus(200);
    }

    return $response;
  }
}