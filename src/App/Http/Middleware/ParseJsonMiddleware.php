<?php 

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class ParseJsonMiddleware
{
  public function __invoke(ServerRequestInterface $request, callable $next){
    $contentType = $request->getHeaderLine('Content-Type');

    if (strpos($contentType, 'application/json') !== false) {
      $data = json_decode($request->getBody()->getContents(), true);
      $request = $request->withParsedBody($data);
    }

    return $next($request);
  }
}