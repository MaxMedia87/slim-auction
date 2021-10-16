<?php

declare(strict_types=1);

use App\Http\Action\HomeAction;
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

$app->get('/hello/{name}', HomeAction::class);

$app->run();