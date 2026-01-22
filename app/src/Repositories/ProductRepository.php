<?php

namespace App\Repositories;

use App\DB\Database;
use App\Model\Product;
use LogicException;

class ProductRepository
{
    private Database $db;
    private ProductImageRepository $imageRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->db = new Database(config());
        $this->categoryRepository = new CategoryRepository();
        $this->imageRepository = new ProductImageRepository();
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): ?Product
    {
        $product = $this->db->table('products')->where('id', '=', $id)->first();

        if (!$product) {
            return null;
        }
        $product =  new Product(
            (int)$product['id'],
            $product['title'],
            (int)$product['category_id']
        );

        $category = $this->categoryRepository->getById($product->getCategoryId());
        $images = $this->imageRepository->findByProductId($product->getId());

        $product->setCategory($category);
        $product->setImages($images);

        return $product;
    }

    public function create(Product $product): Product
    {
        $title = $product->getTitle();
        $categoryId = $product->getCategoryId();


        $newProduct = $this->db->table('products')->insert(['title' => $title, 'category_id' => $categoryId]);

        return new Product(
            $newProduct,
            $title,
            $categoryId
        );
    }

    public function update(Product $product): Product
    {
        if ($product->getId() === null) {
            throw new LogicException('Cannot update product without ID');
        }

        $this->db
            ->table('products')
            ->where('id', '=', $product->getId())
            ->update([
                'title' => $product->getTitle(),
                'category_id' => $product->getCategoryId(),
            ]);

        return new Product(
            $product->getId(),
            $product->getTitle(),
            $product->getCategoryId()
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->table('products')->where('id', '=', $id)->delete();
    }

    public function getByCategoryId(int $categoryId): ?array
    {
        return null;
    }

    public function getByTitle(string $title): ?array
    {
        return null;
    }


    /**
     * @return array
     */
    public function getAll(): array
    {
        $rows = $this->db->table('products')->get();
        return $this->fullProducts($rows);
    }
    public function search(array $filters): array
    {
        $db = $this->db->table('products');

        if (array_key_exists('search', $filters) && !empty($filters['search'])) {
            $db->whereFullText(['title'], $filters['search']);
        }

        if (array_key_exists('category_id', $filters) && !empty($filters['category_id'])) {
            $db->where('category_id', '=', (int)$filters['category_id']);
        }

        $sort = $filters['sort'] ?? 'id';
        $orderBy  = strtoupper($filters['orderBy'] ?? 'DESC');

        if (in_array($sort, ['id', 'title'])) {
            $db->orderBy($sort, $orderBy === 'ASC' ? 'ASC' : 'DESC');
        }

        $page = max(1, (int)($filters['page'] ?? 1));
        $perPage = 10;
        $db->limit($perPage)->offset(($page - 1) * $perPage);

        $rows = $db->get();

        return $this->fullProducts($rows);
    }

    private function fullProducts(array $rows): array
    {
        $products = [];
        $productIds = [];
        $categoryIds = [];

        foreach ($rows as $row) {
            $product = new Product(
                (int)$row['id'],
                $row['title'],
                (int)$row['category_id']
            );

            $products[$product->getId()] = $product;
            $productIds[] = $product->getId();
            $categoryIds[] = $product->getCategoryId();
        }

        $categories = $this->categoryRepository->findByIds(array_unique($categoryIds));
        $images = $this->imageRepository->findByProductIds($productIds);

        foreach ($products as $product) {
            if (isset($categories[$product->getCategoryId()])) {
                $product->setCategory($categories[$product->getCategoryId()]);
            }

            if (isset($images[$product->getId()])) {
                $product->setImages($images[$product->getId()]);
            }
        }
        return array_values($products);
    }
}