<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;
use Http\Models\Classes\PropertyType;

class Furniture extends Product {
    private $props = [
        'height' => PropertyType::NUMERIC,
        'width' => PropertyType::NUMERIC,
        'length' => PropertyType::NUMERIC,
    ];

    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'dimensions: ' . $properties['height'] . 'x' . $properties['width'] . 'x' . $properties['length'];
    }
}