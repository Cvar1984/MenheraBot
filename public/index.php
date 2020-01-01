<?php
use Slim\App;
use Slim\Container;
use Cvar1984\TelegramBot\SearchEngine;
require __DIR__ . '/../vendor/autoload.php';
$container = new Container();
$app = new App($container);
$container = $app->getContainer();
$container['serps'] = function ($container) {
    return new SearchEngine;
};

$app->post(
    '/serps',
    function ($request, $response) {
        $args = $request->getParsedBody();
        if (isset($args['query'])) {
            if (is_string($args['query'])) {
                $serps = $this->get('serps');
                $message = $serps::getHtml($args['query']);
                $status_code = 200;
                $error = false;
            } else {
                $message = 'Sorry we only accept string';
                $status_code = 417;
                $error = true;
            }
        } else {
            $message = 'Sorry query is empty';
            $status_code = 417;
            $error = true;
        }

        $data = [
            'error' => $error,
            'message' => $message
        ];

        return $response
            ->withJson($data, $status_code)
            ->withHeader('Allow', 'GET, POST');
    }
);

$app->run();
