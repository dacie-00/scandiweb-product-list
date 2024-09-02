<?php
declare(strict_types=1);

namespace App;

use App\Interfaces\PersistableInterface;
use PDO;

class Database
{
    private string $host;
    private string $name;
    private string $username;
    private string $password;
    private static Database $instance;
    private PDO $connection;

    public function __construct(
        string $host,
        string $name,
        string $username,
        string $password,
    ) {
        $this->host = $host;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;

        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$name;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->connection = new PDO(
            $dsn,
            $this->username,
            $this->password,
            $options
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query($query, $parameters = []): array
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database(
                $_ENV['DB_HOST'],
                $_ENV['DB_NAME'],
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
            );
        }
        return self::$instance;
    }

    public function migrate(): void
    {
        $this->query("DROP TABLE IF EXISTS product_attributes;");
        $this->query("DROP TABLE IF EXISTS products;");

        $this->query(
            "CREATE TABLE products (
                id VARCHAR(50) PRIMARY KEY,
                sku VARCHAR(50) NOT NULL UNIQUE,
                name VARCHAR(100) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                type VARCHAR(100) NOT NULL 
            );"
        );

        $this->query(
            "CREATE TABLE product_attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id VARCHAR(50) NOT NULL,
                attribute VARCHAR(100) NOT NULL,
                value VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            );"
        );
    }
}