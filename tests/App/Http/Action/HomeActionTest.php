<?php 

namespace Tests\App\Http\Action;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use App\Http\Action\HomeAction;

class HomeActionTest extends TestCase
{
  public function testGuest(){
    $action = new HomeAction();
    $request = new ServerRequest();
    $response = $action($request);

    self::assertEquals(200, $response->getStatusCode());
    self::assertEquals('Hello Guest!', $response->getBody()->getContents());
  }

  public function testWithName(){
    $action = new HomeAction();
    $request = (new ServerRequest)->withQueryParams(["name" => "Jhon"]);
    $response = $action($request);

    self::assertEquals("Hello Jhon!", $response->getBody()->getContents());
  }
}