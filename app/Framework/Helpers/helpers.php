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

if (!function_exists('getCssFile')) {
    function getCssFile()
    {
        // get CSS files urls
        $hot = "hot.css";
        $build = "build/style.css";

        if (file_exists($hot)) {
            return $hot;
        } elseif (file_exists($build)) {
            return $build;
        }

        return null; // no CSS available
    }
}