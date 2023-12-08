<?php

namespace Http\Models\Classes;

// PROBABLY IT'S BETTER TO STORE DATA TYPES ON DB??
class ProductProperty {
    const NUMERIC = 'numeric';
    const STRING = 'string';
    const PRODUCT_PROPERTIES = [
        'dvd' => [
            'size' => self::NUMERIC,
        ],
        'book' => [
            'weight' => self::NUMERIC,
        ],
        'furniture' => [
            'height' => self::NUMERIC,
            'width' => self::NUMERIC,
            'length' => self::NUMERIC,
        ],
    ];

    /**
     * Get the type of a specific property for a given product.
     *
     * @param string $productName
     * @param string $propertyName
     * @return string|null
     */
    public static function getProductPropertyType($productName, $propertyName) {
        $productName = strtolower($productName);
        $propertyName = strtolower($propertyName);
        return self::PRODUCT_PROPERTIES[$productName][$propertyName] ?? null;
    }
}