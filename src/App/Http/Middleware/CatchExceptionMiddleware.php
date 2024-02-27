<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

use Psr\Http\Message\ServerRequestInterface;


class CatchExceptionMiddleware
{
  public $debug;

  public function __construct($debug = false){
    $this->debug = $debug;
  }

  public function __invoke(ServerRequestInterface $request, callable $next)
  {
    try{
      return $next($request);
    }catch (\Throwable $e){

      if(!$this->debug){
        return new HTMLResponse('Internal server error', 500);
      }


      return new JsonResponse([
        'error' => 'Server Error',
        'code' => $e->getCode(),
        'message' => $e->getMessage()
      ], 500);
    };
  }
}