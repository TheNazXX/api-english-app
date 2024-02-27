<?php 

namespace Framework\Http\Pipeline;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Framework\Http\Pipeline\Next;


class Pipeline
{
  private $queue;

  public function __construct(){
    $this->queue = new \SplQueue();
  }

  
  public function __invoke(ServerRequestInterface $request, callable $next){

    $delegate = new Next(clone $this->queue, $next);
    return $delegate($request);
  }

  public function pipe(callable $middleware){
    $this->queue->enqueue($middleware);
  }
}