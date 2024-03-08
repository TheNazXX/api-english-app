<?php

namespace Tests\Framework\Http\Middlware;

use PHPUnit\Framework\TestCase;
use Framework\Container\Container;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Framework\Http\MiddlewareResolver;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

use App\Http\Middleware\NotFoundHandler;

class MiddlewareResolverTest extends TestCase
{

  /**
   * @dataProvider getValidData
   * @param $handler
   */

  public function testDirect($handler)
  {
    $resolver = new MiddlewareResolver(new DummyContainer());
    $middleware = $resolver->resolve($handler);

    $response = $middleware(
      (new ServerRequest())->withAttribute('attribute', $value = 'value'),
      new Response(),
      new NotFoundMiddleware()
    );

    self::assertEquals([$value], $response->getHeader('X-Header'));
  }

  
  /**
   * @dataProvider getValidData
   * @param $handler
   */

  public function testNext($handler)
  {
    $resolver = new MiddlewareResolver(new DummyContainer());
    $middleware = $resolver->resolve($handler);

    $response = $middleware(
      (new ServerRequest())->withAttribute('next', true),
      new Response(),
      new NotFoundMiddleware()
    );

    self::assertEquals(404, $response->getStatusCode());

  }

  public static function getValidData(){
    return [
      'Callable Callback' => [
        function(ServerRequestInterface $request, callable $next){
          if($request->getAttribute('next')){
            return $next($request);
          }

          return (new HtmlResponse(''))
          ->withHeader('X-Header', $request->getAttribute('attribute'));
        }
      ],
      'Callable Class' => [CallableMiddleware::class],
      'Callable Object' => [new CallableMiddleware()],
      'DoublePass Callback' => [function (ServerRequestInterface $request, ResponseInterface $response, callable $next){
        if($request->getAttribute('next')){
          return $next($request);
        }
        return $response->withHeader('X-Header', $request->getAttribute('attribute'));
      }],
      'DoublePass Class' => [DoublePassMiddleware::class],
      'DoublePass Object' =>  [new DoublePassMiddleware],
      'Psr Class' => [PsrMiddleware::class],
      'Psr Object' => [new PsrMiddleware()]
    ];
  }
  

  public function testArray(){
    $resolver = new MiddlewareResolver(new DummyContainer());
    $middleware = $resolver->resolve([
      new DummyMiddleware(),
      new CallableMiddleware()
    ]);

    $response = $middleware(
      (new ServerRequest())->withAttribute('attribute', $value = 'value'),
      new Response(),
      new NotFoundHandler()
    );

    self::assertEquals(['dummy'], $response->getHeader('X-Dummy'));
    self::assertEquals([$value], $response->getHeader('X-Header'));
  }
}

class CallableMiddleware
{
  public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
  {
    if($request->getAttribute('next')){
      return $next($request);
    }

    return (new HTMLResponse(''))
    ->withHeader('X-Header', $request->getAttribute('attribute'));
  }
}

class DoublePassMiddleware
{
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
  {
    if($request->getAttribute('next')){
      return $next($request);
    }

    return $response
    ->withHeader('X-Header', $request->getAttribute('attribute'));
  }
}

class PsrMiddleware implements MiddlewareInterface
{
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    if($request->getAttribute('next')){
      return $handler->handle($request);
    }

    return (new HTMLResponse(''))
    ->withHeader('X-Header', $request->getAttribute('attribute'));
  }
}

class NotFoundMiddleware
{
  public function __invoke(ServerRequestInterface $request){
    return new EmptyResponse(404);
  }
}

class DummyMiddleware
{
  public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface{
    return $next($request)->withHeader('X-Dummy', 'dummy');
  }
}

class DummyContainer extends Container
{
  public function get($id){
    if(!class_exists($id)){
      throw new ServiceNotFoundException($id);
    }

    return new $id();
  } 

  public function has($id): bool{
    return class_exists($id);
  }
}