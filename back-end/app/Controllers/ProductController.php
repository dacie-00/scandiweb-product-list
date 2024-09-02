<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    public function index(): array
    {
        return Product::getAll();
    }

    public function show(string $id): array
    {
        return ['i am product nr. ' . $id];
    }
}