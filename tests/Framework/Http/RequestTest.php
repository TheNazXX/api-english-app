<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Framework\Http\Request;

class RequestTest extends TestCase
{
  public function testEmpty()
  {
    $request = new Request();

    self::assertEquals([], $request->getQueryParams());
    self::assertNull($request->getParsedBody());
  }

  public function testWithQuery()
  {
    $request = (new Request())->withQueryParams($data = [
      'name' => 'Nazar',
      'age' => 21
    ]);

    self::assertEquals($data, $request->getQueryParams());
    self::assertNull($request->getParsedBody());
  }
}