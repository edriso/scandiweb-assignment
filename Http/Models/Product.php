<?php

namespace Http\Models;

use Core\Database;
use Http\Models\Classes\ProductProperty;
use InvalidArgumentException;
use ReflectionClass;

abstract class Product {
    protected $sku;
    protected $name;
    protected $price;
    protected $typeId;
    protected $properties;

    public function __construct($attributes) {
        $this->validateMandatoryFields($attributes);

        $this->sku = $attributes['sku'];
        $this->name = $attributes['name'];
        $this->price = $attributes['price'];
        $this->typeId = $attributes['type_id'];
        $this->properties = $attributes['properties'] ?? [];

        $this->validateProperties($this->properties);
    }

    protected function getInstanceTypeName() {
        return (new ReflectionClass($this))->getShortName();
    }

    protected function validateMandatoryFields($fields) {
        $mandatoryFields = ['sku', 'name', 'price', 'type_id'];

        foreach ($mandatoryFields as $field) {
            if (!isset($fields[$field]) || empty($fields[$field])) {
                throw new InvalidArgumentException("Missing or empty field: $field");
            }
        }
    }

    protected function validateProperties($properties) {
        $instanceTypeName = $this->getInstanceTypeName();
        $expectedProperties = $this->getProperties();

        if (!is_array($properties) || count($properties) !== count($expectedProperties)) {
            throw new InvalidArgumentException("{$instanceTypeName} properties must include: " . implode(', ', $expectedProperties));
        }

        foreach ($expectedProperties as $propName) {
            $this->validateSingleProperty($propName, $properties);
        }
    }

    protected function validateSingleProperty($propName, $properties) {
        if (!isset($properties[$propName])) {
            throw new InvalidArgumentException("Missing property: $propName");
        }

        $providedValue = $properties[$propName];
        $expectedType = ProductProperty::getProductPropertyType($this->getInstanceTypeName(), $propName);

        if (!$this->isValidType($providedValue, $expectedType)) {
            throw new InvalidArgumentException("Invalid type for property $propName. Expected: $expectedType");
        }
    }

    protected function isValidType($value, $expectedType) {
        if ($expectedType === ProductProperty::NUMERIC) {
            return is_numeric($value);
        } elseif ($expectedType === ProductProperty::STRING) {
            return is_string($value);
        } else {
            return false;
        }
    }

    protected static function getTypeName($typeId) {
        $db = new Database();
        $query = 'SELECT type_name FROM product_types WHERE id = ?';
        $productType = $db->query($query, [$typeId])->getOneOrFail();
        return ucfirst($productType['type_name']);
    }

    protected static function areValidIds(array $ids) {
        foreach ($ids as $id) {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }
        }
    
        return true;
    }

    public static function getAll() {
        $db = new Database();
        $query = 'SELECT p.id, p.sku, p.name, p.price, p.type_id, p.properties, t.type_name 
                  FROM products p
                  JOIN product_types t ON p.type_id = t.id
                  ORDER BY p.id DESC';
        
        $productsData = $db->query($query)->get();
    
        $products = [];
        
        foreach ($productsData as $productData) {
            $productType = 'Http\Models\Classes\ProductTypes\\' . ucfirst($productData['type_name']);
            $productData['description'] = $productType::description(json_decode($productData['properties'], true));
            $products[] = $productData;
        }
    
        return $products;
    }

    public static function create($attributes) {
        $productType = 'Http\Models\Classes\ProductTypes\\' . self::getTypeName($attributes['type_id']);

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

    public static function deleteByIds(array $productIds) {
        if (!self::areValidIds($productIds)) {
            abort('Invalid product(s) ID.', 400);
        }

        $db = new Database();
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $query = "DELETE FROM products WHERE id IN ($placeholders)";
        $db->query($query, $productIds);
    }

    abstract protected function getProperties(): array;
    abstract public static function description(array $properties): string;
}