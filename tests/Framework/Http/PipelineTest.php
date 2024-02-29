<?php 

// namespace Tests\Framework\Http\Pipeline;

// use Laminas\Diactoros\Response;

// use Laminas\Diactoros\ServerRequest;
// use PHPUnit\Framework\TestCase;
// use Framework\Http\Pipeline\Pipeline;
// use Psr\Http\Message\ServerRequestInterface;
// use Laminas\Diactoros\Response\JsonResponse;

// class PipelineTest extends TestCase
// {
//   public function testPipe(){
//     $pipeline = new Pipeline();

//     $pipeline->pipe(new Middleware1());
//     $pipeline->pipe(new Middleware2());


//     $response = $pipeline(new ServerRequest(), new Last());


//     self::assertJsonStringEqualsJsonString(
//       json_encode(['middleware' => 1, 'middleware' => 2]),
//       $response->getBody()->getContents()
//     );
//   }
// }

// class Middleware1
// {
//   public function __invoke(ServerRequestInterface $request, $next){
//     return $next($request->withAttribute('middleware', 1));
//   }
// }

// class Middleware2
// {
//   public function __invoke(ServerRequestInterface $request, $next){
//     return $next($request->withAttribute('middleware', 2));
//   }
// }

// class Last 
// {
//   public function __invoke(ServerRequestInterface $request){
//     return new JsonResponse($request->getAttributes());
//   }
// }