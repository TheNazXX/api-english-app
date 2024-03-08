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

  public function testAutowiring(){
    $container = new Container();
    $outer = $container->get(Outer::class);

    self::assertNotNull($outer);
    self::assertInstanceOf(Outer::class, $outer);

    self::assertNotNull($middle = $outer->middle);
    self::assertInstanceOf(Middle::class, $middle);

    self::assertNotNull($inner = $middle->inner);
    self::assertInstanceOf(Inner::class, $inner);
  }

  public function testAutowiringScalarWithDefault(){
    $container = new Container();

    $scalar = $container->get(ScalarWithArrayAndDefault::class);
    
    self::assertNotNull($scalar);
    self::assertNotNull($inner = $scalar->inner);
    self::assertInstanceOf(Inner::class, $inner);
    
    self::assertEquals(10, $scalar->default);
  }

  public function testAutoInstantiating(){
    $container = new Container();

    self::assertNotNull($value1 = $container->get(\stdClass::class));
    self::assertNotNull($value2 = $container->get(\stdClass::class));

    self::assertInstanceOf(\stdClass::class, $value1);
    self::assertInstanceOf(\stdClass::class, $value2);

    self::assertSame($value1, $value2);
  }

  public function testNotFound(){
    $container = new Container();
    $this->expectException(ServiceNotFoundException::class);
    $container->get('email');
  }
}

class Outer
{
  public $middle;

  public function __construct(Middle $middle){
    $this->middle = $middle;
  }
}

class Middle
{
  public $inner;

  public function __construct(Inner $inner){
    $this->inner = $inner;
  }
}

class Inner{}

class ScalarWithArrayAndDefault
{
  public $inner;
  public $array;
  public $default;

  public function __construct(Inner $inner, array $array, $default = 10){
    $this->inner = $inner;
    $this->default = $default;
    $this->array = $array;
  }
}