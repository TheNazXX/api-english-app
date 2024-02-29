<?php 

namespace Framework\Http\Pipeline;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\Pipeline\Next;


class Pipeline
{
  private $queue; 


  
  public function __construct(){
    $this->queue = new \SplQueue();
  }

  
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next){
  
    $delegate = new Next(clone $this->queue, $next);
    return $delegate($request, $response);
  }

  public function pipe($middleware){
    $this->queue->enqueue($middleware);
  }
}