<?php
declare(strict_types=1);

namespace App\Controllers;

class ProductController
{
    public function index(): array
    {
        return ['products' => 'product list'];
    }

    public function show(string $id): array
    {
        return ['i am product nr. ' . $id];
    }
}