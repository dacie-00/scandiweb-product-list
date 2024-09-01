<?php
declare(strict_types=1);

use App\Controllers\ProductController;

return [
    ['GET', '/products', [ProductController::class, 'index']],
    ['GET', '/products/{id}', [ProductController::class, 'show']],
];