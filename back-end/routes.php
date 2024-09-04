<?php
declare(strict_types=1);

use App\Controllers\ProductController;

return [
    ['DELETE', '/products', [ProductController::class, 'delete']],
    ['GET', '/products', [ProductController::class, 'index']],
];