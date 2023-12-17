<?php

namespace App\Models\ProductTypes;

use App\Core\DataType;
use App\Models\Product;

class DVD extends Product
{
    private $props = [
        'size' => DataType::NUMERIC,
    ];

    protected function getProperties(): array
    {
        return $this->props;
    }

    public static function description(array $properties): string
    {
        return 'size: ' . $properties['size'] . ' MB';
    }
}