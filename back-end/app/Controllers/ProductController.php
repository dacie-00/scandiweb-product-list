<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Logger;
use App\Models\Book;
use App\Models\Dvd;
use App\Models\Furniture;
use App\Models\Product;

class ProductController
{
    public function index(): array
    {
        return Product::getAll();
    }

    public function delete(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        foreach($data['products'] as $product) {
            Product::delete($product);
        }
    }

    public function store(): void
    {
        $productMap = [
            'book' => Book::class,
            'dvd' => Dvd::class,
            'furniture' => Furniture::class,
        ];

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['product']['type']) && class_exists($productMap[$data['product']['type']])) {
            $product = $productMap[$data['product']['type']];
//            $product::validate();
            $product::fromJson($data['product'])->create();
        }
    }
}