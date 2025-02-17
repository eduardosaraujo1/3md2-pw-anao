<?php

namespace App\Framework\View;

class Engine
{
    private \League\Plates\Engine $platesEngine;

    private static Engine $_instance;
    private function __construct($extension)
    {
        // declare params
        $defaultPath = realpath(__DIR__ . '/templates');
        $viewPath = realpath(__DIR__ . '/../../../resources/views');

        // create plate engine with folder
        $this->platesEngine = new \League\Plates\Engine($defaultPath, $extension);
        $this->platesEngine->addFolder('view', $viewPath, true);
    }

    private static function singleton(): Engine
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Engine("phtml");
        }
        return self::$_instance;
    }

    public static function render(string $name, array $data = [])
    {
        // support dot syntax for folder pathing
        $name = str_replace('.', '/', $name);

        // Render the engine
        try {
            $result = Engine::get()->render("view::$name", $data);
        } catch (\League\Plates\Exception\TemplateNotFound $e) {
            $message = $e->getMessage();
            $result = Engine::get()->render("view::error", [
                "error" => "Página não encontrada. Detalhes: $message"
            ]);
        }

        return $result;
    }

    public static function get()
    {
        return static::singleton()->platesEngine;
    }
}