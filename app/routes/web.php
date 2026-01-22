<?php

/** @var \App\Router $router */

use App\Controller\MainController;
use App\Controller\ProductController;

$router->get('/', [MainController::class, 'index']);
$router->get('/add_product', [ProductController::class, 'addForm']);
$router->post('/product_store', [ProductController::class, 'store']);

