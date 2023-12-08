<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;

class Furniture extends Product {
    private $props = ['height', 'width', 'length'];

    protected function getProperties(): array {
        return $this->props;
    }

    public static function description(array $properties): string {
        return 'Dimensions: ' . $properties['height'] . 'x' . $properties['width'] . 'x' . $properties['length'];
    }
}