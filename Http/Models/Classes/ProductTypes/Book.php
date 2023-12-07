<?php

namespace Http\Models\Classes\ProductTypes;

use Http\Models\Product;

class Book extends Product {
    protected function getProperties(): array {
        return [
            'weight' => 'numeric'
        ];
    }

    public function getAdditionalInfo(): string {        
        return 'Weight: ' . $this->properties['weight'] . ' KG';
    }
}