<?php

namespace App\Framework\View;

class Engine
{
    private \League\Plates\Engine $platesEngine;

    private static Engine $_instance;
    private function __construct()
    {
        $this->platesEngine = new \League\Plates\Engine(__DIR__ . '/templates');
        $this->platesEngine->addFolder('view', __DIR__ . '/../../../views');
    }

    public static function singleton(): Engine
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Engine();
        }
        return self::$_instance;
    }
}