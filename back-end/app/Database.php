<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private string $host;
    private string $name;
    private string $username;
    private string $password;
    private PDO $connection;

    public function __construct(
        string $host,
        string $name,
        string $username,
        string $password,
    )
    {
        $this->host = $host;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;

        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
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
}