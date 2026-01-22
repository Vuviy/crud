<?php

namespace App\Model;

class Category
{
    public function __construct(
        private ?int $id,
        private string $title,
        private ?int $parent_id
    ) {}

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParentId(): int
    {
        return $this->parent_id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setParentId(int $categoryId): void
    {
        $this->parent_id = $categoryId;
    }
}