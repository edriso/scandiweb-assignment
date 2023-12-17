<?php

function base_path($path)
{
    return BASE_PATH . $path;
}

function app_path($path)
{
    return base_path('App/' . $path);
}

function generate_controller_namespace($fileName)
{
    return 'App\Http\Controllers\\' . $fileName;
}

function generate_product_type_namespace($fileName)
{
    return 'App\Http\Models\ProductTypes\\' . $fileName;
}

function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    die();
}