<?php

namespace App\Controller;

use App\Model\Product;
use App\Repositories\ProductRepository;
use App\Request;
use App\Response;
use App\Services\ImageUploader;
use App\View;

class ProductController
{
    private ProductRepository $repository;
    private ImageUploader $imageUploader;

    public function __construct()
    {
        $this->repository = new ProductRepository();
        $this->imageUploader = new ImageUploader();
    }

    public function addForm(): Response
    {
       return new Response(View::make('product/addForm'));
    }

//    public function store(Request $request): Response
//    {
//
//        dd($request->all());
//
//
//        $product = new Product(
//            null,
//            $data['title'],
//            (int)$data['category_id']
//        );
//
//        $this->repository->create($product);
//
//        return new Response('good');
//    }


    public function store(Request $request): Response
    {
        $product = new Product(
            null,
            $request->post('title'),
            (int)$request->post('category_id')
        );

        $product = $this->repository->create($product);

        $images = $request->files('images');

        $this->imageUploader->uploadForProduct($product->getId(), $images);

        return new Response('Created', 201);
    }


}