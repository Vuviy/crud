<?php

namespace App\Controller;

use App\Model\Product;
use App\Repositories\ProductRepository;
use App\Response;
use App\View;

class MainController
{

    public function index(): Response
    {



        $repo = new ProductRepository();



        $products = $repo->search([
            'search'           => 'prod1',
            'sort'        => 'title',
            'orderBy'         => 'DESC',
            'page'        => 1,
        ]);


        dd($products);

        $products = $repo->getAll();


        return new Response(View::make('home', ['products' => $products]));
    }
}