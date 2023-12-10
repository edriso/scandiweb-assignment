<?php

function base_path($path) {
    return BASE_PATH . $path;
}

function dd($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    die();
}