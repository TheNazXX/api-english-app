<?php 


namespace Tests\Framework\Container;

use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
  public function testPrimitives(){
    $container = new Container();

    $container->set($id = 'id', $value = 5);
    self::assertEquals($value, $container->get($id));

    $container->set($id = 'id', $value = 'string');
    self::assertEquals($value, $container->get($id));

    $container->set($id = 'id', $value = ['array']);
    self::assertEquals($value, $container->get($id));

    $container->set($id = 'id', $value = new \stdClass());
    self::assertEquals($value, $container->get($id));
  }

  public function testCallback(){
    $container = new Container();

    $container->set($id = 'id', function(){
      return new \stdClass();
    });

    self::assertNotNull($value = $container->get($id));
    self::assertInstanceOf(\stdClass::class, $value);
  }

  public function testSingleton(){
    $container = new Container();

    $container->set($id = 'id', function(){
      return new \stdClass();
    });

    self::assertNotNull($value1 = $container->get($id));
    self::assertNotNull($value2 = $container->get($id));

    self::assertSame($value1, $value2);
  }

  public function testContainerPass(){
    $container = new Container();

    $container->set('param', $value = 15);
    $container->set($id = 'id', function(Container $container){

      $object = new \stdClass();
      $object->param = $container->get('param');
      return $object;
    });

    self::assertObjectHasProperty('param', $object = $container->get($id));
    self::assertEquals($value, $object->param);
  }

  public function testNotFound()
  {
    $container = new Container();
    $this->expectException(ServiceNotFoundException::class);
    $container->get('email');
  }
}