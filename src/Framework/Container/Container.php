<?php

namespace Framework\Container;
use Framework\Container\ServiceNotFoundException;

class Container
{
  private $definitions = [];
  private $caching = []; 

  public function get($id)
  {

    if(array_key_exists($id, $this->caching)){
      return $this->caching[$id];
    }

    if(!array_key_exists($id, $this->definitions)){
      throw new ServiceNotFoundException('Undefined parameter"'. $id .'"');
    }

    $definition = $this->definitions[$id];

    if($definition instanceof \Closure){
      $this->caching[$id] = $definition($this);
    } else{
      $this->caching[$id] = $definition;
    }

    return $this->caching[$id];
  }

  public function set($id, $value)
  {
    if(array_key_exists($id, $this->caching)){
      unset($this->caching[$id]);
    };

    $this->definitions[$id] = $value; 
  }
}