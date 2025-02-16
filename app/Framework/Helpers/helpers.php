<?php

use App\Framework\View\Engine;

if (!function_exists('view')) {
    function view(string $name, array $params = [])
    {
        return Engine::render($name, $params);
    }
}