<?php 

use App\Http\Actions\HomeAction;
use App\Http\Actions\AboutAction;
use App\Http\Actions\Blog;
use App\Http\Actions\ProfileAction;

use App\Http\Middleware\AuthMiddleware;

$app->get('home', '/', HomeAction::class);
$app->get('about', '/about', AboutAction::class);
$app->get('blog', '/blog', Blog\IndexAction::class);

$app->get('profile', '/profile', [
  $container->get(AuthMiddleware::class),
  new ProfileAction()
]);

$app->get('blog_show', '/blog/{id}', Blog\ShowAction::class, ['tokens' => ['id' => '\d+']]);