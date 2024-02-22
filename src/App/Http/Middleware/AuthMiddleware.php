<?php 

namespace App\Http\Middleware;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
  public const ATTRIBUTE = '_user';

  private $users = null;

  public function __construct($users){
    $this->users = $users;
  }

  public function __invoke(ServerRequestInterface $request, $next){
    $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
    $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

    foreach($this->users as $name => $pass){
      if($username === $name && $pass === $password){
        return ($next)($request->withAttribute(self::ATTRIBUTE, $username));
      }
    }

    return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
  }
}