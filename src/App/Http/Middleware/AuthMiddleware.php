<?php 

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Laminas\Diactoros\Response\EmptyResponse;


class AuthMiddleware implements MiddlewareInterface
{
  public const ATTRIBUTE = '_user';

  private $users = null;
  private $response;

  public function __construct($users, $responsePrototype){
    $this->users = $users;
    $this->response = $responsePrototype;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{
    $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
    $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

    foreach($this->users as $name => $pass){
      if($username === $name && $pass === $password){
        return $handler->handle($request->withAttribute(self::ATTRIBUTE, $username));
      }
    }


    return $this->response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', 'Basic realm=Restricted are');
  }

  // public function __invoke(ServerRequestInterface $request, $next){
  //   $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
  //   $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

  //   foreach($this->users as $name => $pass){
  //     if($username === $name && $pass === $password){
  //       return ($next)($request->withAttribute(self::ATTRIBUTE, $username));
  //     }
  //   }

  //   return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
  // }
}