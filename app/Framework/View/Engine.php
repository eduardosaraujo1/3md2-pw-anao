<?php

namespace App\Framework\View;

class Engine
{
    private const EXTENSION = "phtml";
    public \League\Plates\Engine $engine;

    // singleton
    private static Engine $_instance;
    private function __construct()
    {
        // declare params
        $defaultPath = realpath(__DIR__ . '/defaults');
        $viewPath = realpath(__DIR__ . '/../../../resources/views');

        // create plate engine with folder
        $this->engine = new \League\Plates\Engine($defaultPath, self::EXTENSION);
        $this->engine->addFolder('view', $viewPath, true);
    }

    public static function singleton(): Engine
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Engine();
        }
        return self::$_instance;
    }
    // end singleton

    public function render(string $name, array $data = []): string
    {
        // Support dot syntax for folder pathing
        $name = str_replace('.', '/', $name);

        try {
            $result = $this->engine->render("view::$name", $data);
        } catch (\League\Plates\Exception\TemplateNotFound $e) {
            $message = $e->getMessage();
            $result = $this->engine->render("view::error", [
                "code" => 500,
                "message" => "Erro ao renderizar página. Detalhes: $message",
            ]);
        }

        return $result;
    }
}