<?php

namespace App\Framework\View;

use App\Framework\Exception\ViewDirectoryNotFoundException;

class Engine
{
    public const EXTENSION = "phtml";
    public \League\Plates\Engine $engine;

    public function __construct()
    {
        // declare params
        $defaultPath = realpath(__DIR__ . '/defaults');
        $viewPath = realpath(PROJECT_ROOT . '/resources/views');

        // throw exceptions if paths were not found
        if (!$defaultPath) {
            throw new ViewDirectoryNotFoundException("Folder 'defaults' required for view engine not found");
        }

        if (!$viewPath) {
            throw new ViewDirectoryNotFoundException("Folder 'resources/views' required for view engine not found");
        }

        // create plate engine with folder
        $this->engine = new \League\Plates\Engine($defaultPath, self::EXTENSION);
        $this->engine->addFolder('view', $viewPath, true);
    }

    /**
     * Use Plates's Engine to render a .phtml view
     * @param string $name View name (syntax: file path with dot notation starting from resources/views/). Example: 'layout.app' => 'resources/views/layout/app.phtml'
     * @param array<string,mixed> $data Data to be passed to the view
     */
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