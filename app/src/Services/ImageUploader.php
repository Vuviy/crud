<?php

namespace App\Services;

use App\DB\Database;
use Exception;

class ImageUploader
{
    private Database $db;
    private const ALLOWED_TYPES = [
        'image/jpeg',
        'image/png',
    ];
    private const MAX_SIZE = 2_000_000;

    public function __construct()
    {
        $this->db = new Database(config());
    }

    public function uploadForProduct(int $productId, array $files): void
    {
        foreach ($this->normalizeFilesArray($files) as $file) {
            $this->validate($file);
            $filename = $this->generateFilename($file['name']);
            $destination = __DIR__ . '/../../public/uploads/' . $filename;

            move_uploaded_file($file['tmp_name'], $destination);

            $this->saveToDatabase($productId, $filename);
        }
    }

    private function normalizeFilesArray(array $files): array
    {
        $normalized = [];

        foreach ($files['name'] as $index => $name) {
            $normalized[] = [
                'name' => $name,
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index],
            ];
        }

        return $normalized;
    }

    private function validate(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error');
        }

        if (!in_array($file['type'], self::ALLOWED_TYPES, true)) {
            throw new Exception('Invalid file type');
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new Exception('File too large');
        }
    }

    private function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        return uniqid('product_', true) . '.' . $extension;
    }

    private function saveToDatabase(int $productId, string $filename): void
    {
        $this->db
            ->table('product_images')
            ->insert([
                'product_id' => $productId,
                'path' => $filename,
            ]);
    }

}