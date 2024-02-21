<?php

namespace App\Http\Actions\Blog;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class IndexAction
{
  public function __invoke(){
    return new JsonResponse([
      'title_1' => 'title-1',
      'title_2' => 'title-2',
      'title_3' => 'title-3',
      'title_4' => 'title-4',
    ]);
  }
}