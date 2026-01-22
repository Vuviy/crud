<?php

namespace App\Repositories;

use App\DB\Database;
use App\Model\Category;
use App\Model\Product;
use LogicException;

class CategoryRepository
{

    private Database $db;

    public function __construct()
    {
        $this->db = new Database(config());
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $categoriesRow = $this->db->table('categories')->get();

        $categories = [];

        foreach ($categoriesRow as $categoryRow) {
            $categories[] = new Product($categoryRow['id'], $categoryRow['title'], $categoryRow['parent_id']);
        }

        return $categories;
    }

    public function getById(int $id): ?Category
    {
        $category = $this->db->table('categories')->where('id', '=', $id)->first();

        if (!$category) {
            return null;
        }

        return new Category(
            (int)$category['id'],
            $category['title'],
            (int)$category['category_id']
        );
    }

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $rows = $this->db
            ->table('categories')
            ->whereIn('id', $ids)
            ->get();

        $categories = [];
        foreach ($rows as $row) {
            $categories[$row['id']] = new Category(
                $row['id'],
                $row['title'],
                $row['parent_id'],
            );
        }

        return $categories;
    }

    public function create(Category $category): Category
    {
        $title = $category->getTitle();
        $parentId = $category->getParentId();
        
        $newCategory = $this->db->table('categories')->insert(['title' => $title, 'parent_id' => $parentId]);

        return new Category(
            $newCategory,
            $title,
            $parentId
        );
    }

    public function update(Category $category): Category
    {
        if ($category->getId() === null) {
            throw new LogicException('Cannot update category without ID');
        }

        $this->db
            ->table('categories')
            ->where('id', '=', $category->getId())
            ->update([
                'title' => $category->getTitle(),
                'parent_id' => $category->getParentId(),
            ]);

        return new Category(
            $category->getId(),
            $category->getTitle(),
            $category->getParentId()
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->table('categories')->where('id', '=', $id)->delete();
    }

}