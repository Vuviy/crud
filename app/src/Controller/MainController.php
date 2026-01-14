<?php

namespace App\Controller;

use App\Response;
use App\View;

class MainController
{

    public function index(): Response
    {
        return new Response(View::make('home'));
    }
}