<?php

namespace App\Http\Models;

use App\Core\Database;
use App\Core\Response;
use ReflectionClass;
use App\Http\Validations\ProductValidation;

abstract class Product
{
    protected $sku;
    protected $name;
    protected $price;
    protected $typeId;
    protected $properties;

    public function __construct($attributes)
    {
        ProductValidation::validateMandatoryFields($attributes);

        $this->sku = $attributes['sku'];
        $this->name = $attributes['name'];
        $this->price = $attributes['price'];
        $this->typeId = $attributes['type_id'];
        $this->properties = $attributes['properties'] ?? [];

        ProductValidation::validateProperties(
            $this->properties,
            $this->getInstanceTypeName(),
            $this->getProperties()
        );
    }

    public static function getAll()
    {
        $db = new Database();
        $query = 'SELECT p.id, p.sku, p.name, p.price, p.type_id, p.properties, t.name AS type 
                  FROM products p
                  JOIN product_types t ON p.type_id = t.id
                  ORDER BY p.id DESC';
        $productsData = $db->query($query)->get();

        $products = [];
        foreach ($productsData as $productData) {
            $productType = generate_product_type_namespace(ucfirst($productData['type']));
            $productData['description'] = $productType::description(json_decode($productData['properties'], true));
            unset($productData['properties'], $productData['type_id']);
            $products[] = $productData;
        }

        return $products;
    }

    public static function create($attributes)
    {
        if (!isset($attributes['type_id'])) {
            Response::abort('Missing type_id', 400);
        }

        $productType = generate_product_type_namespace(self::getTypeName($attributes['type_id']));
        $productInstance = new $productType($attributes);

        $db = new Database();
        $query = 'INSERT INTO products (sku, name, price, type_id, properties) 
                    VALUES (:sku, :name, :price, :type_id, :properties)';

        $db->query($query, [
            'sku' => htmlspecialchars($productInstance->sku),
            'name' => htmlspecialchars($productInstance->name),
            'price' => htmlspecialchars($productInstance->price),
            'type_id' => htmlspecialchars($productInstance->typeId),
            'properties' => json_encode($productInstance->properties),
        ]);

        return get_object_vars($productInstance);
    }

    public static function deleteByIds(array $productIds)
    {
        ProductValidation::validateIds($productIds);

        $db = new Database();
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $query = "DELETE FROM products WHERE id IN ($placeholders)";
        $db->query($query, $productIds);
    }

    protected function getInstanceTypeName()
    {
        return (new ReflectionClass($this))->getShortName();
    }

    protected static function getTypeName($typeId)
    {
        $db = new Database();
        $query = 'SELECT name FROM product_types WHERE id = ?';
        $productType = $db->query($query, [$typeId])->getOneOrFail();
        return ucfirst($productType['name']);
    }

    abstract protected function getProperties(): array;
    abstract public static function description(array $properties): string;
}