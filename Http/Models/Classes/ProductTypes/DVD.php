<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;
use Http\Models\Classes\PropertyType;

class DVD extends Product {
    private $props = [
        'size' => PropertyType::NUMERIC,
    ];
    
    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'size: ' . $properties['size'] . ' MB';
    }
}