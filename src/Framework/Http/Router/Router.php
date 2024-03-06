<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\RouteData;

interface Router
{
  public function match(ServerRequestInterface $request): ?Result;
  public function generate($name, array $params = []): ?string;
  public function addRoute(RouteData $data);
}