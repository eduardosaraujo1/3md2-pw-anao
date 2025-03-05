<?php

namespace Core;

use Core\Abstract\Singleton;

class Vite extends Singleton
{
    private string $buildPath;
    /** @var array<string,mixed> */
    private array $build;
    private bool $buildExists;
    private string $hotFilePath;
    private string $hotfileUrl;
    private bool $hotfileExists;

    protected function __construct()
    {
        $host = 'localhost';
        $port = $_ENV['VITE_PORT'] ?? '5173';

        $build = base_path('public/build') ?? '';
        $hotfile = base_path('public/hot') ?? '';

        $this->buildExists = is_dir($build);
        $this->hotfileExists = file_exists($hotfile);

        if ($this->buildExists) {
            $this->buildPath = $build;
            $this->build = self::readBuild($this->buildPath);
        }

        if ($this->hotfileExists) {
            $this->hotFilePath = $hotfile;
            $this->hotfileUrl = "http://$host:$port";
        }
    }

    private static function readBuild(string $buildPath): array
    {
        $manifestPath = realpath("$buildPath/.vite/manifest.json");

        if ($manifestPath && file_exists($manifestPath)) {
            return json_decode(file_get_contents($manifestPath), true);
        }

        return self::scanBuildAssets($buildPath);
    }

    private static function scanBuildAssets(string $buildPath): array
    {
        $assets = [];

        $scanAssets = function ($path) use (&$assets, &$scanAssets, $buildPath) {
            foreach (glob($path . '/*') as $file) {
                if (is_dir($file)) {
                    $scanAssets($file);
                } elseif (preg_match('/\.(css|js)$/', $file)) {
                    $assets[] = str_replace($buildPath, '', $file);
                }
            }
        };

        $scanAssets($buildPath);

        return $assets;
    }

    private function resolveTag(string $file): string
    {
        $html = htmlspecialchars($file, ENT_QUOTES);

        if (str_contains($file, '.css')) {
            return "<link rel='stylesheet' href='$html'></link>";
        }
        if (str_contains($file, '.js')) {
            return "<script type='module' src='$html'></script>";
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $resources
     */
    private function resolveBuildTags(array $resources): string
    {
        if (!$this->buildExists) {
            return '';
        }

        $html = '';
        foreach ($resources as $resource) {
            if (!isset($this->build[$resource])) {
                continue;
            }
            $asset = $this->build[$resource];

            $file = "build/{$asset['file']}";
            $html .= $this->resolveTag($file);
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $resources
     */
    private function resolveDevTags(array $resources): string
    {
        if (!$this->hotfileExists) {
            return '';
        }

        $html = '';
        foreach ($resources as $resource) {
            $trimmed = trim($resource, '/');

            $url = "{$this->hotfileUrl}/$trimmed";
            $tag = $this->resolveTag($url);

            $html .= $tag;
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $resources
     */
    public function compile(array $resources): string
    {
        $html = '';

        // decide if should use development server or built assets
        if ($this->hotfileExists) {
            $html .= $this->resolveDevTags($resources);
        } else if ($this->buildExists) {
            $html .= $this->resolveBuildTags($resources);
        }

        return $html;
    }
}