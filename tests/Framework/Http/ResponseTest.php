<?php
namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Response;


class ResponseTest extends TestCase
{
  public function testResponse(){
    $response = new Response($body = 'Body', 200);

    self::assertEquals($body, $response->getBody());
    self::assertEquals(200, $response->getStatusCode());
    self::assertEquals('OK', $response->getReasonPhrase());
  }

  public function testHeaders()
  {
    $response = (new Response('', 200))->withHeader($header = 'X-Developer', $value = 'Nazar');
    
    self::assertEquals(true, $response->hasHeader($header));
    self::assertEquals('Nazar', $response->getHeader($header));
    self::assertEquals([$header => $value], $response->getHeaders());
  }
}