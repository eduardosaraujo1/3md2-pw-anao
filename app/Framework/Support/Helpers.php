<?php

use App\Framework\View\Engine;

if (!function_exists('view')) {
    function view(string $name, array $params = [])
    {
        try {
            $str = Engine::render($name, $params);
        } catch (\League\Plates\Exception\TemplateNotFound $e) {
            // get message
            $message = $e->getMessage();

            // render message
            $str = Engine::render("view::error", [
                "error" => "Página não encontrada. Detalhes: $message"
            ]);
        }

        return $str;
    }
}
