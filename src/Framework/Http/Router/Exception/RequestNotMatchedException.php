<?php 

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RequestNotMatchedException extends \LogicException
{
  private $request;

  public function __construct(ServerRequestInterface $request){
    parent::__construct('Matches Not Found');
    $this->request = $request;
  }

  public function getRequest(): ServerRequestInterface
  {
    return $this->request;
  }
}