<?php 

namespace Framework\Container;

interface ContainerInterface
{
  public function get($id);
  public function has($id): bool;
}