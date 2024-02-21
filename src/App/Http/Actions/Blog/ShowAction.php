<?php

namespace App\Http\Actions\Blog;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class ShowAction
{
  public function __invoke(ServerRequestInterface $request){

    $id = $request->getAttribute('id');

    return new JsonResponse([
      "title_$id" => "title-$id"
    ]);
  }
}