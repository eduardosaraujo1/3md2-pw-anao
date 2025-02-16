<?php

namespace App\Framework\View;

class Engine
{
    private \League\Plates\Engine $platesEngine;

    private static Engine $_instance;
    private function __construct()
    {
        $this->platesEngine = new \League\Plates\Engine(__DIR__ . '/templates');
        $this->platesEngine->addFolder('view', realpath(__DIR__ . '/../../../resources/views'));
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