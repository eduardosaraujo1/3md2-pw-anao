<?php

use App\Framework\View\Engine;

if (!function_exists('view')) {
    function view(string $name, array $params)
    {
        $engine = Engine::singleton();
        // TODO: implement the method below:
        // $engine->render($name, $params)
    }
}