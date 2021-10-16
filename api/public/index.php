<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

http_response_code(500);

require __DIR__ . './../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG')
    ]
]);

$container = $builder->build();

$app = AppFactory::create();

$app->addErrorMiddleware($container->get('config')['debug'], true, true);

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

    $response->getBody()->write("Hello $name");

    return $response;
});

$app->run();