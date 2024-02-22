<?php 

namespace App\Http\Actions;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Middleware\AuthMiddleware;

class ProfileAction
{
  public function __invoke(ServerRequestInterface $request)
  {
    $username = $request->getAttribute(AuthMiddleware::ATTRIBUTE);
    return new HTMLResponse("I'm logged in as $username");
  }
}