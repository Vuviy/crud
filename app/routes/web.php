<?php

/** @var \App\Router $router */

use App\Controller\MainController;

$router->get('/', [MainController::class, 'index']);

