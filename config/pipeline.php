<?php 


use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\CatchExceptionMiddleware;


$app->pipe($container->get(CatchExceptionMiddleware::class));
$app->pipe($container->get(TimerMiddleware::class));

$app->pipe($container->get(RouteMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));