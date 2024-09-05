<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Dvd extends Product implements \JsonSerializable
{
    private float $size;

    public function __construct(
        string $sku,
        string $name,
        int $price,
        float $size,
        string $id = null
    ) {
        parent::__construct($sku, $name, $price, $id);

        $this->size = $size;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
    }

    public static function get(string $id): ?Dvd
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

        return new Dvd(
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
            'INSERT INTO product_attributes (product_id, attribute, value) VALUES (?, "size", ?)',
            [
                $this->getId(),
                $this->getSize(),
            ]
        );
    }

    public static function fromDb(array $data): Dvd
    {
        return new Dvd(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['value'],
            $data['id'],
        );
    }

    public static function fromJson(array $data): Dvd
    {
        return new Dvd(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['size'],
        );
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => 'dvd',
            'size' => $this->getSize(),
        ];
    }
}