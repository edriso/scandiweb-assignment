<?php

namespace App\Http\Models\ProductTypes;

use App\Core\DataType;
use App\Http\Models\Product;

class Furniture extends Product
{
    private $props = [
        'height' => DataType::NUMERIC,
        'width' => DataType::NUMERIC,
        'length' => DataType::NUMERIC,
    ];

    protected function getProperties(): array
    {
        return $this->props;
    }

    public static function description(array $properties): string
    {
        return 'dimensions: ' . $properties['height'] . 'x' . $properties['width'] . 'x' . $properties['length'];
    }
}