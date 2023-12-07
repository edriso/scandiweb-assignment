<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;

class Furniture extends Product {
    private $props = ['height', 'width', 'length'];

    protected function getProperties(): array {
        return $this->props;
    }

    public function getAdditionalInfo(): string {
        return 'Dimensions: ' . $this->properties['height'] . 'x' . $this->properties['width'] . 'x' . $this->properties['length'];
    }
}