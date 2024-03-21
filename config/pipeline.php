<?php 


use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\CatchExceptionMiddleware;
use App\Http\Middleware\ParseJsonMiddleware;
use App\Http\Middleware\CorsMiddleware;

$app->pipe($container->get(CatchExceptionMiddleware::class));
$app->pipe($container->get(CorsMiddleware::class));
$app->pipe($container->get(ParseJsonMiddleware::class));

$app->pipe(TimerMiddleware::class);
$app->pipe(RouteMiddleware::class);
$app->pipe(DispatchMiddleware::class);