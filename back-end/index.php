<?php
declare(strict_types=1);

use App\Database;
use App\Models\Book;
use App\Models\Product;
use App\ProductDatabase;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

require_once "vendor/autoload.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Request-Methods: *");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$exists = Database::getInstance()->query(
    "SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1",
    [getenv('DB_NAME'), 'products']
);
if (empty($exists)) {
    Database::getInstance()->migrate();
}
//$dotenv->load();
//$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD']);

//$book = (new Book("foobair", "foobar", 51, 15, 'fooi'));
//$book->save();
//Book::get('foo')->delete();
//var_dump(Book::get('foo'));die;
//var_dump(Product::getAll());die;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $routes = include __DIR__ . "/routes.php";
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);


$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        (new Response())
            ->setStatusCode(Response::HTTP_NOT_FOUND)
            ->send();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        (new Response())
            ->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
            ->send();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handle = $routeInfo[1];
        $vars = $routeInfo[2];

        [$class, $method] = $handle;
        http_response_code(200);
        echo json_encode((new $class())->$method(...array_values($vars)));
        break;
}