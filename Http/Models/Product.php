<?php

namespace Http\Models;

use Core\Database;
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

        // Validate properties based on product type
        self::validateProperties($this->properties);
        // validate for sku if exists // UNFINISHED
    }

    // public function getSKU() {
    //     return $this->sku;
    // }

    // public function getName() {
    //     return $this->name;
    // }

    // public function getPrice() {
    //     return $this->price;
    // }

    protected function getInstanceTypeName() {
      return (new ReflectionClass($this))->getShortName();
    }

    public static function create($attributes) {
        $typeId = $attributes['type_id'];
        if (!isset($typeId)) {
            throw new \InvalidArgumentException('Missing "typeId" key');
        }

        $productType = 'Http\Models\Classes\ProductTypes\\' . self::getProductType($typeId);
        
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

    protected static function getProductType($typeId) {
        $db = new Database();
        $query = 'SELECT type_name FROM product_types WHERE id = ?';
        $productName = $db->query($query, [$typeId])->getOneOrFail();
        return ucfirst($productName['type_name']);
    }

    protected static function isValidType($value, $expectedType) {
        // Implement your custom type validation logic here
        // For simplicity, this example assumes the provided type is a string
        // You may need to enhance this based on your specific requirements
    
        // Remove whitespaces and convert to lowercase for case-insensitive comparison
        $expectedType = strtolower(str_replace(' ', '', $expectedType));
    
        switch ($expectedType) {
            case 'numeric':
                return is_numeric($value);
            case 'string':
                return is_string($value);
            // Add more cases as needed
    
            default:
                return false;
        }
    }

    protected function validateProperties($properties) {
        if (
            !is_array($properties) || count($properties) !== count($this->getProperties())
        ) {
            throw new InvalidArgumentException("{$this->getInstanceTypeName()} properties must include: " . implode(', ', array_keys($this->getProperties())));
        }

        // Check if the provided property values match the expected data types
        foreach ($this->getProperties() as $propName => $expectedType) {
            if (!isset($properties[$propName])) {
                throw new InvalidArgumentException("Missing property: $propName");
            }

            $providedValue = $properties[$propName];
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

    abstract protected function getProperties(): array;
    abstract public function getAdditionalInfo(): string;
}