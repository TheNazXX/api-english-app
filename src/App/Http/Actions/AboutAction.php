<?php

namespace App\Http\Actions;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class AboutAction
{
  public function __invoke(){
    return new HTMLResponse('About my site');
  }
}