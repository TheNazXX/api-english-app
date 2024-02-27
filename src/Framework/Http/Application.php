<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Framework\Http\MiddlewareResolver;

class Application extends Pipeline{
  private $resolver;


  public function __construct(MiddlewareResolver $resolver){
    parent::__construct();
    $this->resolver = $resolver;
  }

  public function pipe($middleware){
    parent::pipe($this->resolver->resolve($middleware));
  }
}