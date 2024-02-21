<?php 

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Pipeline\Pipeline;

use Laminas\Stratigility\MiddlewarePipe;
;

class Application
{
  private $resolver;
  private $default;
  private $middlewarePipe;

  public function __construct(MiddlewareResolver $resolver,  MiddlewarePipe $middlewarePipe, callable $default){
    $this->middlewarePipe = $middlewarePipe;
    $this->resolver = $resolver;
    $this->default = $default;
  }

  public function pipe($path, $middleware = null): MiddlewarePipe{

    if($middleware === null){
      return $this->middlewarePipe->pipe($this->resolver($path, $this));
    }

    $this->middlewarePipe->pipe($this->resolver->resolve($middleware));
    // parent::pipe($this->resolver->resolve($middleware));
  }

  public function run(ServerRequestInterface $request, ResponseInterface $response){
    return $this($request, $response, $this->default);
  }
}