<?php

namespace App\Framework\View;

class Engine
{
    private \League\Plates\Engine $platesEngine;

    private static Engine $_instance;
    private function __construct()
    {
        // declare params
        $extension = "phtml";
        $defaultPath = realpath(__DIR__ . '/templates');
        $viewPath = realpath(__DIR__ . '/../../../resources/views');

        // create plate engine with folder
        $this->platesEngine = new \League\Plates\Engine($defaultPath, $extension);
        $this->platesEngine->addFolder('view', $viewPath, true);
    }

    private static function singleton(): Engine
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Engine();
        }
        return self::$_instance;
    }

    public static function render(string $name, array $data = [])
    {
        $name = str_replace('.', '/', $name);
        return Engine::get()->render("view::$name", $data);
    }

    public static function get()
    {
        return static::singleton()->platesEngine;
    }
}