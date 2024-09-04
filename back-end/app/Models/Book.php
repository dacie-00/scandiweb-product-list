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

    public function create(): void
    {
        $db = Database::getInstance();

        $db->query(
            'INSERT INTO products (id, sku, name, price, type) VALUES (?, ?, ?, ?, ?)',
            [
                $this->getId(),
                $this->getSku(),
                $this->getName(),
                $this->getPrice(),
                __CLASS__,
            ]
        );
        $db->query(
            'INSERT INTO product_attributes (product_id, attribute, value) VALUES (?, "weight", ?)',
            [
                $this->getId(),
                $this->getWeight(),
            ]
        );
    }

    public function save(): void
    {
        $existingProduct = Database::getInstance()->query('SELECT id FROM products WHERE id = ?', [$this->getId()]);

        if ($existingProduct) {
            $db = Database::getInstance();
            $db->query(
                'UPDATE products SET sku = ?, name = ?, price = ?, type = ? WHERE id = ?',
                [
                    $this->getSku(),
                    $this->getName(),
                    $this->getPrice(),
                    __CLASS__,
                    $this->getId()
                ]
            );
            $db->query(
                'UPDATE product_attributes SET value = ? WHERE product_id = ? AND attribute = "weight"',
                [
                    $this->getWeight(),
                    $this->getId()
                ]
            );
        } else {
            $this->create();
        }
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

//    public function delete(): void
//    {
//        Database::getInstance()->query(
//            'DELETE FROM products WHERE id = ? AND type = ?',
//            [$this->getId(), __CLASS__]
//        );
//    }

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


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'weight' => $this->getWeight(),
        ];
    }
}