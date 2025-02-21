<?php

use App\Framework\Facades\ViewEngine;
use App\Framework\Http\Response;
use App\Framework\View\Engine;

if (!function_exists('view')) {
    /**
     * Render a view into a string with passed params
     * @param string $name path to view starting from `resources/views/` with dot notation and no extension. 
     * Example: 'layout.app' => 'resources/views/layout/app.phtml'
     * @param array<string,mixed> $params Parameters to be passed into the view as variables
     */
    function view(string $name, array $params = []): string
    {
        $str = ViewEngine::render($name, $params);

        return $str;
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect response
     * @param string $location Location to redirect the user to
     */
    function redirect(string $location): Response
    {
        return new Response(
            status: 301,
            headers: ["Location: $location"]
        );
    }
}