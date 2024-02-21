<?php 

namespace Tests\App\Http\Action\Blog;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\JsonResponse;
use App\Http\Action\Blog\IndexAction;

class IndexActionTest extends TestCase
{
  public function testSuccess(){
    $action = new IndexAction();
    $response = $action();

    self::assertEquals(200, $response->getStatusCode());
    self::assertJsonStringEqualsJsonString(
      json_encode([
        [
          'id' => 2, 'Title' => 'Title 1'
        ],
        [
          'id' => 1, 'Title' => 'Title 2'
        ]
        ]), 
      $response->getBody()->getContents());
  }
}