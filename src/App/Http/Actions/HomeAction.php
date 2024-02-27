<?php 

namespace App\Http\Actions;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class HomeAction
{
  public function __invoke(ServerRequestInterface $request, callable $next = null)
  {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HTMLResponse("Hello, $name!");
  }
}