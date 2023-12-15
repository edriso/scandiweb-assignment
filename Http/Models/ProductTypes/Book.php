<?php

namespace Http\Models\ProductTypes;

use Core\DataType;
use Http\Models\Product;

class Book extends Product {
    private $props = [
        'weight' => DataType::NUMERIC,
    ];

    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'weight: ' . $properties['weight'] . ' KG';
    }
}