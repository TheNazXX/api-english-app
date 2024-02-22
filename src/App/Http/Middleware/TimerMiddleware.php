<?php 

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;


class TimerMiddleware
{
  public function __invoke(ServerRequestInterface $request, $next){
    $start = microtime(true);
    $response = $next($request);
    $end = microtime(true);
    return $response->withHeader('X-Timer', $end - $start);
  }
}