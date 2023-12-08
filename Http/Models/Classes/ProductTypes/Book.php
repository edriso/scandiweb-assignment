<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;

class Book extends Product {
    private $props = ['weight'];

    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'Weight: ' . $properties['weight'] . ' KG';
    }
}