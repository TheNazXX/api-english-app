<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

use Framework\Http\Router\Exception\RequestNotMatchedException;

use Framework\Http\Router\Router;
use Framework\Http\Router\RouteCollection;

class RouterTest extends TestCase
{
  public function testRoutes()
  {
    $map = new RouteCollection();

    $map->get('home', '/', $handler_get_home = 'handler_get');
    $map->get('blog', '/blog', $handler_get_blog = 'handler_get');

    $router = new Router($map);
    $result = $router->match($this->buildRequest('GET', '/'));

    self::assertEquals('home', $result->getName());
    self::assertEquals($handler_get_home, $result->getHandler());
  }

  public function testMissingMethods()
  {
    $map = new RouteCollection();

    $map->get('home', '/', $handler_get_home = 'handler_get');

    $router = new Router($map);

    $this->expectException(RequestNotMatchedException::class);
    $router->match($this->buildRequest("DELETE", '/'));
  }

  public function testCorrectAttributes()
  {
    $map = new RouteCollection();

    $map->get($name = 'posts', '/posts/{id}', $handler_get_home = 'handler_get', ["id" => "\d+"]);


    $router = new Router($map);
    $result = $router->match($this->buildRequest("GET", '/posts/5'));

    self::assertEquals($name, $result->getName());
    self::assertEquals(['id' => 5], $result->getAttributes());


  }

  public function buildRequest($method, $uri)
  {
    return (new ServerRequest())
    ->withMethod($method)
    ->withUri(new Uri($uri));
  }

  public function testGenerate()
  {
    $map = new RouteCollection();

    $map->get($name = 'blog_show', '/blog/{id}', $handler_get_home = 'handler_get', ['id' => '\d+']);
    $router = new Router($map);

    self::assertEquals('/blog/5', $router->generate($name, ['id' => 5]));
  }
}