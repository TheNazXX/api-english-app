<?php 

namespace Tests\App\Http\Action\Blog;

use Laminas\Diactoros\ServerRequest;
use App\Http\Action\Blog\ShowAction;
use PHPUnit\Framework\TestCase;
use App\Http\Middleware\NotFoundHandler;

class ShowActionTest extends TestCase
{
  public function testSuccess(){
    $request = (new ServerRequest())->withAttribute('id', $id = 2);
    $action = new ShowAction();
    $response = $action($request, new NotFoundHandler());

    self::assertEquals(200, $response->getStatusCode());
    self::assertJsonStringEqualsJsonString(
      json_encode(
        ['id' => $id, 'Title' => 'Post #' . $id]
      ),
      $response->getBody()->getContents()
    );
  }

  public function testNotFound(){
    $request = (new ServerRequest())->withAttribute('id', $id = 6);
    $action = new ShowAction();
    $response = $action($request, new NotFoundHandler());

    self::assertEquals(404, $response->getStatusCode());
    self::assertEquals('Not Found Page', $response->getBody()->getContents());
  }
}