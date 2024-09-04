<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Logger;
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
}