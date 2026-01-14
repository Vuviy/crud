<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';


use App\Router;

$router = new Router();

require __DIR__ . '/routes/web.php';

$response = $router->dispatch();

$response->send();
