<?php

use App\Framework\Facades\ViewEngine;
use App\Framework\View\Engine;

if (!function_exists('view')) {
    function view(string $name, array $params = [])
    {
        try {
            $str = ViewEngine::render($name, $params);
        } catch (\League\Plates\Exception\TemplateNotFound $e) {
            // get message
            $message = $e->getMessage();

            // render message
            $str = ViewEngine::render("view::error", [
                "error" => "Página não encontrada. Detalhes: $message"
            ]);
        }

        return $str;
    }
}
