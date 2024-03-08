<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;
use Framework\Container\ServiceNotFoundException;

class Container implements ContainerInterface
{
  private $definitions = [];
  private $caching = [];


  public function __construct(array $definitions = []){
    $this->definitions = $definitions;
  }

  public function get($id)
  {


    if(array_key_exists($id, $this->caching)){
      return $this->caching[$id];
    }

    if(!array_key_exists($id, $this->definitions)){
      if(class_exists($id)){
        $reflection = new \ReflectionClass($id);

        $arguments = [];

        if(($constructor = $reflection->getConstructor()) !== null){
          
          foreach($constructor->getParameters() as $param){
            if($param->getType() instanceof \ReflectionNamedType && !$param->getType()->isBuiltin()){  // Если тип является именованным типом (классом)
              $arguments[] = $this->get($param->getType()->getName());   
            }else if($param->getType() && $param->getType()->getName() === "array"){
   
              $arguments[] = [];
            }else{
              if(!$param->isDefaultValueAvailable()){
                throw new ServiceNotFoundException('Unable to resolve"'. $param->getName() .'"" in service'. $id .'"');
              }

              $arguments[] = $param->getDefaultValue();
            }
          }
        }
        
        $result = $reflection->newInstanceArgs($arguments);
        return $this->caching[$id] =  $result;
      }

      throw new ServiceNotFoundException('Unknow service "'. $id .'"');
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

  public function has($id): bool {
    return array_key_exists($id, $this->definitions) || class_exists($id); 
  }
}