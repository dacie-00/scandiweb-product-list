<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use App\Interfaces\PersistableInterface;
use Ramsey\Uuid\Uuid;

abstract class Product
{
    private string $id;
    private string $sku;
    private string $name;
    private int $price;

    public function __construct(
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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
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
        foreach ($productsData as $productData) {
            $products[] = $productData['type']::fromDb($productData);
        }
        return $products;
    }

    public static function delete(string $id): void
    {
        Database::getInstance()->query(
            'DELETE FROM products WHERE id = ?',
            [$id]
        );
    }
}