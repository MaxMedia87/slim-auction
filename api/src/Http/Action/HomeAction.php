<?php
declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeAction
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $name = $args['name'];

        $response->getBody()->write("Hello $name");

        return $response;
    }
}