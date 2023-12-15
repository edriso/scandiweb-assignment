<?php

namespace Http\Models\ProductTypes;

use Core\DataType;
use Http\Models\Product;

class DVD extends Product {
    private $props = [
        'size' => DataType::NUMERIC,
    ];
    
    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'size: ' . $properties['size'] . ' MB';
    }
}