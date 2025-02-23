<?php

namespace App\Framework\Support;

class Vite
{
    private string $env;
    private string $port;
    private bool $isProd;
    private ?string $manifestPath;
    /** @var array<string,mixed> */
    private array $manifest = [];

    // singleton
    private static self $_instance;

    public static function singleton(): Vite
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // end singleton

    private function __construct()
    {
        $this->port = $_ENV['VITE_PORT'] ?? '5173';
        $this->isProd = strtolower($_ENV['APP_ENV'] ?? '') !== 'local';
        $this->manifestPath = realpath(__DIR__ . '/../../../public/build/.vite/manifest.json') ?? null;

        if (file_exists($this->manifestPath)) {
            $this->manifest = self::readManifest($this->manifestPath);
        }
    }

    private static function readManifest($path)
    {
        return json_decode(file_get_contents($path), true);
    }

    private function resolveTag(string $file)
    {
        $html = htmlspecialchars($file, ENT_QUOTES);

        if (str_contains($file, '.css')) {
            return "<link rel='stylesheet' href='$html'></link>";
        }
        if (str_contains($file, '.js')) {
            return "<script type='module' src='$html'></script>";
        }

        return '';
    }

    /**
     * @param array<string,mixed> $resources
     */
    public function compile(array $resources): string
    {
        $html = '';

        // resolve build assets
        foreach ($resources as $resource) {
            if (!isset($this->manifest[$resource])) {
                continue;
            }
            $asset = $this->manifest[$resource];

            $file = "build/{$asset['file']}";
            $html .= $this->resolveTag($file);
        }

        // resolve vite server assets
        if (!$this->isProd) {
            $host = "http://localhost:{$this->port}";
            foreach ($resources as $resource) {
                $trimmed = trim($resource, '/');

                $url = "$host/$trimmed";
                $tag = $this->resolveTag($url);

                $html .= $tag;
            }
        }

        return $html;
    }
}