<?php 


use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use App\Http\Middleware\TimerMiddleware;
use App\Http\Middleware\CatchExceptionMiddleware;


$app->pipe(CatchExceptionMiddleware::class);
$app->pipe(TimerMiddleware::class);

$app->pipe(RouteMiddleware::class);
$app->pipe(DispatchMiddleware::class);