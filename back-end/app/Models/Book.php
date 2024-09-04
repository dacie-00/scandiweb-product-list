<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Book extends Product implements \JsonSerializable
{
    private float $weight;

    public function __construct(
        string $sku,
        string $name,
        int $price,
        float $weight,
        string $id = null
    ) {
        parent::__construct($sku, $name, $price, $id);

        $this->weight = $weight;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public static function get(string $id): ?Book
    {
        $data = Database::getInstance()->query(
            'SELECT 
                p.sku, 
                p.name, 
                p.price, 
                pa.value, 
                p.id 
            FROM 
                products p 
            INNER JOIN 
                product_attributes pa 
            ON 
                p.id = pa.product_id 
            WHERE 
                p.id = ? 
            AND 
                p.type = ?',
            [$id, __CLASS__]
        );

        if (empty($data)) {
            return null;
        }

        return new Book(
            $data[0]['sku'],
            $data[0]['name'],
            (int)$data[0]['price'],
            (float)$data[0]['value'],
            $data[0]['id'],
        );
    }

    public function create(): void
    {
        $db = Database::getInstance();

        parent::create();

        $db->query(
            'INSERT INTO product_attributes (product_id, attribute, value) VALUES (?, "weight", ?)',
            [
                $this->getId(),
                $this->getWeight(),
            ]
        );
    }

    public function update(): void
    {
        $db = Database::getInstance();

        parent::create();

        $db->query(
            'UPDATE product_attributes SET value = ? WHERE product_id = ? AND attribute = "weight"',
            [
                $this->getWeight(),
                $this->getId()
            ]
        );
    }

    public function save(): void
    {
        $existingProduct = self::exists($this->getId());
        if ($existingProduct) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public static function fromDb(array $data): Book
    {
        return new Book(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['value'],
            $data['id'],
        );
    }

    public static function fromJson(array $data): Book
    {
        return new Book(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['weight'],
        );
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => 'book',
            'weight' => $this->getWeight(),
        ];
    }
}