<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use App\Interfaces\PersistableInterface;
use Ramsey\Uuid\Uuid;

abstract class Product
{
    protected string $id;
    protected string $sku;
    protected string $name;
    protected int $price;

    protected function __construct(
        string $sku,
        string $name,
        int $price,
        string $id = null,
    )
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    protected function getId(): string
    {
        return $this->id;
    }

    protected function setId(string $id): void
    {
        $this->id = $id;
    }

    protected function getSku(): string
    {
        return $this->sku;
    }

    protected function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    protected function getName(): string
    {
        return $this->name;
    }

    protected function setName(string $name): void
    {
        $this->name = $name;
    }

    protected function getPrice(): int
    {
        return $this->price;
    }

    protected function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public static function getAll(): array
    {
        $productsData = Database::getInstance()
            ->query('
                SELECT 
                    p.id, 
                    p.sku,
                    p.name, 
                    p.price,
                    p.type,
                    pa.attribute, 
                    pa.value
                FROM 
                    products p
                INNER JOIN 
                    product_attributes pa 
                ON 
                    p.id = pa.product_id;
            ');

        $products = [];
        $groupedProducts = [];

        foreach ($productsData as $row) {
            $productId = $row['id'];

            if (!isset($groupedProducts[$productId])) {
                $groupedProducts[$productId] = [
                    'id' => $row['id'],
                    'sku' => $row['sku'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'type' => $row['type'],
                    'attributes' => [],
                ];
            }

            if (!empty($row['attribute'])) {
                $groupedProducts[$productId]['attributes'][$row['attribute']] = $row['value'];
            }
        }

        foreach ($groupedProducts as $productData) {
            $productClass = $productData['type'];
            if (class_exists($productClass)) {
                $products[] = $productClass::fromDb($productData);
            }
        }

        return $products;
    }

    protected function create(): void
    {
        $db = Database::getInstance();

        $db->query(
            'INSERT INTO products (id, sku, name, price, type) VALUES (?, ?, ?, ?, ?)',
            [
                $this->getId(),
                $this->getSku(),
                $this->getName(),
                $this->getPrice(),
                static::class,
            ]
        );
    }

    protected static function exists(string $id)
    {
        return Database::getInstance()->query('SELECT id FROM products WHERE id = ?', [$id]);
    }

    protected function update(): void
    {
        $db = Database::getInstance();
        $db->query(
            'UPDATE products SET sku = ?, name = ?, price = ?, type = ? WHERE id = ?',
            [
                $this->getSku(),
                $this->getName(),
                $this->getPrice(),
                static::class,
                $this->getId()
            ]
        );
    }

    public static function delete(string $id): void
    {
        Database::getInstance()->query(
            'DELETE FROM products WHERE id = ?',
            [$id]
        );
    }
}