<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;
use Http\Models\Classes\PropertyType;

class Book extends Product {
    private $props = [
        'weight' => PropertyType::NUMERIC,
    ];

    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'Weight: ' . $properties['weight'] . ' KG';
    }
}