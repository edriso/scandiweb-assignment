<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;

class DVD extends Product {
    private $props = ['size'];
    
    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'Size: ' . $properties['size'] . ' MB';
    }
}