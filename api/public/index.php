<?php
declare(strict_types=1);

use App\Http\Action\HomeAction;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;

http_response_code(500);

require __DIR__ . './../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG')
    ],
    ResponseFactoryInterface::class => \DI\get(ResponseFactory::class)
]);

$container = $builder->build();

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware($container->get('config')['debug'], true, true);

$app->get('/hello/{name}', HomeAction::class);

$app->run();