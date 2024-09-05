<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use JsonSerializable;

class Furniture extends Product implements JsonSerializable
{
    private float $width;
    private float $height;
    private float $length;

    public function __construct(
        string $sku,
        string $name,
        int $price,
        float $height,
        float $width,
        float $length,
        string $id = null
    ) {
        parent::__construct($sku, $name, $price, $id);

        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public static function get(string $id): ?Furniture
    {
        $data = Database::getInstance()->query(
            'SELECT 
            p.sku, 
            p.name, 
            p.price, 
            p.id,
            MAX(CASE WHEN pa.attribute_name = "height" THEN pa.value END) AS height,
            MAX(CASE WHEN pa.attribute_name = "width" THEN pa.value END) AS width,
            MAX(CASE WHEN pa.attribute_name = "length" THEN pa.value END) AS length
            FROM 
                products p 
            LEFT JOIN 
                product_attributes pa 
                ON p.id = pa.product_id
            WHERE 
                p.id = ? 
                AND p.type = ?
            GROUP BY 
                p.sku, p.name, p.price, p.id',
        );

        if (empty($data)) {
            return null;
        }

        return new Furniture(
            $data[0]['sku'],
            $data[0]['name'],
            (int)$data[0]['price'],
            (float)$data[0]['height'],
            (float)$data[0]['width'],
            (float)$data[0]['length'],
            $data[0]['id']
        );
    }

    public function create(): void
    {
        $db = Database::getInstance();

        $db->getConnection()->beginTransaction();
        parent::create();
        $db->query(
            'INSERT INTO product_attributes (product_id, attribute, value) VALUES 
                    (?, "width", ?), 
                    (?, "height", ?), 
                    (?, "length", ?)',
            [
                $this->getId(), $this->getWidth(),
                $this->getId(), $this->getHeight(),
                $this->getId(), $this->getLength(),
            ]
        );
        $db->getConnection()->commit();
    }

    public static function fromDb(array $data): Furniture
    {
        return new Furniture(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['attributes']['height'],
            (float)$data['attributes']['width'],
            (float)$data['attributes']['length'],
            $data['id'],
        );
    }

    public static function fromJson(array $data): Furniture
    {
        return new Furniture(
            $data['sku'],
            $data['name'],
            (int)$data['price'],
            (float)$data['height'],
            (float)$data['width'],
            (float)$data['length'],
        );
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => 'furniture',
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
        ];
    }
}