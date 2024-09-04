<?php
declare(strict_types=1);

use App\Controllers\ProductController;

return [
    ['GET', '/products', [ProductController::class, 'index']],
    ['POST', '/products', [ProductController::class, 'store']],
    ['DELETE', '/products', [ProductController::class, 'delete']],
];