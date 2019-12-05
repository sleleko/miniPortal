<?php

use Slim\App;
use App\Container;
use App\Controllers\Api;
use RKA\Middleware\IpAddress;

require dirname(__DIR__) . '/core/vendor/autoload.php';

$container = new Container();
$app = new App($container);
$app->add(new IpAddress());

$app->any('/api[/{name:.+}]', function ($request, $response, $args) use ($container) {
  $container->request = $request;
  $container->response = $response;

  return (new Api($container))->process($request, $response, $args);
});

try {
  $app->run();
} catch (Throwable $e) {
  exit($e->getMessage());
}