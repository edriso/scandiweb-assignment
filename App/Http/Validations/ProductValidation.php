<?php

namespace App\Http\Validations;

use App\Core\DataType;
use App\Core\Response;

class ProductValidation
{
    public static function validateMandatoryFields($fields)
    {
        $mandatoryFields = ['sku', 'name', 'price'];

        foreach ($mandatoryFields as $field) {
            if (!isset($fields[$field]) || empty($fields[$field])) {
                Response::abort("Missing or empty field: $field", 400);
            }

            switch ($field) {
                case 'price':
                    if (!is_numeric($fields[$field]) || $fields[$field] < 0) {
                        Response::abort("Invalid data type for $field. Must be a non-negative number.", 400);
                    }
                    break;
                case 'name':
                    if (!is_string($fields[$field]) || is_numeric($fields[$field])) {
                        Response::abort("Invalid data type for $field. Must be a string.", 400);
                    }
                    break;
                case 'sku':
                    if (!ctype_alnum($fields[$field])) {
                        Response::abort("Invalid value for $field. Must be alphanumeric.", 400);
                    }
                    break;
            }
        }
    }

    public static function validateProperties($properties, $instanceTypeName, $expectedProperties)
    {
        if (!is_array($properties) || count($properties) !== count($expectedProperties)) {
            Response::abort("{$instanceTypeName} properties must include: " . implode(', ', array_keys($expectedProperties)), 400);
        }

        foreach ($expectedProperties as $propName => $propType) {
            if (!isset($properties[$propName])) {
                Response::abort("Missing property: $propName", 400);
            }

            if (!self::isValidType($properties[$propName], $propType)) {
                Response::abort("Invalid data type for property $propName. Expected: $propType", 400);
            }
        }
    }

    public static function isValidType($value, $expectedType)
    {
        if ($expectedType === DataType::NUMERIC) {
            return is_numeric($value);
        } elseif ($expectedType === DataType::STRING) {
            return is_string($value);
        } else {
            return false;
        }
    }

    public static function validateIds(array $idArr)
    {
        foreach ($idArr as $id) {
            if (!is_numeric($id) || $id <= 0) {
                Response::abort('Invalid product(s) ID.', 400);
            }
        }
    }
}