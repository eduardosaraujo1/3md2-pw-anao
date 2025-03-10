<?php

use Core\Http\Response;
use Core\View\AttributeBag;
use Core\View\Engine;
use Core\Vite;

function base_path(string $path): string
{
    return PROJECT_ROOT . "/$path";
}

if (!function_exists('view')) {
    /**
     * Render a view into a string with passed params
     * @param string $name path to view starting from `resources/views/` with dot notation and no extension. 
     * Example: 'layout.app' => 'resources/views/layout/app.phtml'
     * @param array<string,mixed> $params Parameters to be passed into the view as variables
     */
    function view(string $name, array $params = []): string
    {
        $str = Engine::instance()->render($name, $params);

        return $str;
    }
}

if (!function_exists('component')) {
    /**
     * Render a component into a string with passed params
     * @param string $name path to component starting from `resources/views/components/` with dot notation and no extension. 
     * Example: 'primary-button' => 'resources/views/components/primary-button.phtml'
     * @param array<string,mixed> $props Parameters to be passed into the component as variables
     * @param array<string,mixed> $attributes Attribute bag to include in the component
     */
    function component(string $name, array $props = [], array $attributes = []): string
    {
        $path = 'components/' . str_replace('.', '/', $name);

        $bag = new AttributeBag($attributes);
        $params = [
            ...$props,
            'attributes' => $bag
        ];

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
        $header = isset($_SERVER['HTTP_HX_REQUEST']) ? 'HX-Redirect' : 'Location';

        return new Response(
            status: 301,
            headers: ["$header: $location"]
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
        return Vite::instance()->compile($params);
    }
}