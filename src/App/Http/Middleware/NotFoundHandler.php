<?php 

namespace App\Http\Middleware;
use Laminas\Diactoros\Response\HtmlResponse;

class NotFoundHandler
{
  public function __invoke(){
    return new HtmlResponse("Undefined page", 404);
  }
}