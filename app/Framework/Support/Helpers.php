<?php

use App\Framework\Facades\ViewEngine;
use App\Framework\Facades\Vite;
use App\Framework\Http\Response;

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

if (!function_exists('component')) {
    /**
     * Render a component into a string with passed params
     * @param string $name path to component starting from `resources/views/components/` with dot notation and no extension. 
     * Example: 'primary-button' => 'resources/views/components/primary-button.phtml'
     * @param array<string,mixed> $params Parameters to be passed into the component as variables
     */
    function component(string $name, array $params = []): string
    {
        $path = 'components/' . str_replace('.', '/', $name);

        return view($path, $params);
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

if (!function_exists('vite')) {
    /**
     * Create link tags for using Vite resource manager (used for importing default JS and CSS)
     * @param array<string> $params
     */
    function vite(array $params): string
    {
        return Vite::compile($params);
    }
}