<?php

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;

use Framework\Http\Application;
use Framework\Http\ResponseSender;


chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';
require_once 'src/App/helpers/funcs.php';

require 'config/container.php';


// Initialization
$app = $container->get(Application::class);


// Pipelines
require 'config/pipeline.php';

// Routing
require 'config/routes.php';

// Running //
$request = ServerRequestFactory::fromGlobals();

$response = $app->run($request, new Response());
$emitter = new ResponseSender();
$emitter->emit($response);