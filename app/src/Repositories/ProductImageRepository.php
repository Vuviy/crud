<?php

namespace App\Repositories;

use App\DB\Database;

class ProductImageRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database(config());
    }

    public function findByProductIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $rows = $this->db
            ->table('product_images')
            ->whereIn('product_id', $productIds)
            ->get();

        $images = [];
        foreach ($rows as $row) {
            $images[$row['product_id']][] = $row['path'];
        }

        return $images;
    }

    public function findByProductId(int $productId): array
    {
        $rows = $this->db
            ->table('product_images')
            ->where('product_id', '=', $productId)
            ->get();

        $images = [];
        foreach ($rows as $row) {
            $images[] = $row['path'];
        }

        return $images;
    }
}