<?php

namespace Http\Models;

use Core\Database;
use Core\Response;
use ReflectionClass;
use Http\Models\Classes\PropertyType;

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
        $mandatoryFields = ['sku', 'name', 'price'];

        foreach ($mandatoryFields as $field) {
            if (!isset($fields[$field]) || empty($fields[$field])) {
                Response::abort("Missing or empty field: $field", 400);
            }

            switch ($field) {
                case 'price':
                    if (!is_numeric($fields[$field]) || $fields[$field] < 0) {
                        Response::abort("Invalid data type for $field. Must be a non-negative number.", 400);
                    }
                    break;
                case 'name':
                    if (!is_string($fields[$field]) || is_numeric($fields[$field])) {
                        Response::abort("Invalid data type for $field. Must be a string.", 400);
                    }
                    break;
                case 'sku':
                    if (!ctype_alnum($fields[$field])) {
                        Response::abort("Invalid value for $field. Must be alphanumeric.", 400);
                    }
                    break;
            }
        }
    }

    protected function validateProperties($properties) {
        $instanceTypeName = $this->getInstanceTypeName();
        $expectedProperties = $this->getProperties();

        if (!is_array($properties) || count($properties) !== count($expectedProperties)) {
            Response::abort("{$instanceTypeName} properties must include: " . implode(', ', array_keys($expectedProperties)), 400);
        }

        foreach ($expectedProperties as $propName => $propType) {
            if (!isset($properties[$propName])) {
                Response::abort("Missing property: $propName", 400);
            }

            if (!$this->isValidType($properties[$propName], $propType)) {
                Response::abort("Invalid data type for property $propName. Expected: $propType", 400);
            }
        }
    }

    protected function isValidType($value, $expectedType) {
        if ($expectedType === PropertyType::NUMERIC) {
            return is_numeric($value);
        } elseif ($expectedType === PropertyType::STRING) {
            return is_string($value);
        } else {
            return false;
        }
    }

    protected static function getTypeName($typeId) {
        $db = new Database();
        $query = 'SELECT name FROM product_types WHERE id = ?';
        $productType = $db->query($query, [$typeId])->getOneOrFail();
        return ucfirst($productType['name']);
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
        $query = 'SELECT p.id, p.sku, p.name, p.price, p.type_id, p.properties, t.name AS type 
                  FROM products p
                  JOIN product_types t ON p.type_id = t.id
                  ORDER BY p.id DESC';
        
        $productsData = $db->query($query)->get();
    
        $products = [];
        
        foreach ($productsData as $productData) {
            $productType = 'Http\Models\Classes\ProductTypes\\' . ucfirst($productData['type']);
            $productData['description'] = $productType::description(json_decode($productData['properties'], true));
            unset($productData['properties'], $productData['type_id']);
            $products[] = $productData;
        }
    
        return $products;
    }

    public static function create($attributes) {
        if (!isset($attributes['type_id'])) {
            Response::abort('Missing type_id', 400);
        }

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
            Response::abort('Invalid product(s) ID.', 400);
        }

        $db = new Database();
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $query = "DELETE FROM products WHERE id IN ($placeholders)";
        $db->query($query, $productIds);
    }

    abstract protected function getProperties(): array;
    abstract public static function description(array $properties): string;
}