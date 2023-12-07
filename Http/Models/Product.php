<?php

namespace Http\Models;

use Core\Database;
use Http\Models\Classes\ProductProperty;
use ReflectionClass;
use InvalidArgumentException;

abstract class Product {
    protected $sku;
    protected $name;
    protected $price;
    protected $typeId;
    protected $properties;

    public function __construct($attributes) {
        $this->sku = $attributes['sku'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->price = $attributes['price'] ?? null;
        $this->typeId = $attributes['type_id'] ?? null;
        $this->properties = $attributes['properties'] ?? [];

        self::validateProperties($this->properties);
        // validate for sku if exists // UNFINISHED
    }

    protected function getInstanceTypeName() {
      return (new ReflectionClass($this))->getShortName();
    }

    protected function validateProperties($properties) {
        $instanceTypeName = $this->getInstanceTypeName();

        if (
            !is_array($properties) || count($properties) !== count($this->getProperties())
        ) {
            throw new InvalidArgumentException("{$instanceTypeName} properties must include: " . implode(', ', $this->getProperties()));
        }

        // Check if the provided property values match the expected data types
        foreach ($this->getProperties() as $propName) {
            if (!isset($properties[$propName])) {
                throw new InvalidArgumentException("Missing property: $propName");
            }

            $providedValue = $properties[$propName];
            $expectedType = ProductProperty::getProductPropertyType($instanceTypeName, $propName);
            if (!self::isValidType($providedValue, $expectedType)) {
                throw new InvalidArgumentException("Invalid type for property $propName. Expected: $expectedType");
            }
        }
    }

    public static function getAll() {
        $db = new Database();
        $query = 'SELECT id, sku, name, price FROM products';
        $result = $db->query($query)->get();
        return $result;
    }

    public static function create($attributes) {
        $typeId = $attributes['type_id'];
        if (!isset($typeId)) {
            throw new \InvalidArgumentException('Missing "typeId" key');
        }

        $productType = 'Http\Models\Classes\ProductTypes\\' . self::getTypeName($typeId);
        
        $productInstance = new $productType($attributes);

        // Implement logic to insert a new product into the database
        $db = new Database();
        $query = 'INSERT INTO products (sku, name, price, type_id, properties) 
                    VALUES (:sku, :name, :price, :type_id, :properties)';
        
        $db->query($query, [
            'sku' => $productInstance->sku,
            'name' => $productInstance->name,
            'price' => $productInstance->price,
            'type_id' => $productInstance->typeId,
            'properties' => json_encode($productInstance->properties),
        ]);

        return get_object_vars($productInstance);
    }

    protected static function getTypeName($typeId) {
        $db = new Database();
        $query = 'SELECT type_name FROM product_types WHERE id = ?';
        $productType = $db->query($query, [$typeId])->getOneOrFail();
        return ucfirst($productType['type_name']);
    }

    protected static function isValidType($value, $expectedType) {
        if($expectedType === ProductProperty::NUMERIC) {
            return is_numeric($value);
        } else if ($expectedType === ProductProperty::STRING) {
            return is_string($value);
        } else {
            return false;
        }
    }

    abstract protected function getProperties(): array;
    abstract public function getAdditionalInfo(): string;
}