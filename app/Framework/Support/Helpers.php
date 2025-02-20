<?php

use App\Framework\Facades\ViewEngine;
use App\Framework\View\Engine;

if (!function_exists('view')) {
    /**
     * Render a view into a string with passed params
     * @param string $name path to view starting from `resources/views/` with dot notation and no extension. 
     * Example: 'layout.app' => 'resources/views/layout/app.phtml'
     * @param array<string,mixed> $params Parameters to be passed into the view as variables
     * @return string
     */
    function view(string $name, array $params = []): string
    {
        $str = ViewEngine::render($name, $params);

        return $str;
    }
}
