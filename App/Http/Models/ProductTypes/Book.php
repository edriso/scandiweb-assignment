<?php

namespace App\Http\Models\ProductTypes;

use App\Core\DataType;
use App\Http\Models\Product;

class Book extends Product
{
    private $props = [
        'weight' => DataType::NUMERIC,
    ];

    protected function getProperties(): array
    {
        return $this->props;
    }

    public static function description(array $properties): string
    {
        return 'weight: ' . $properties['weight'] . ' KG';
    }
}