<?php

// Define project root path
define('PROJECT_ROOT', dirname(__DIR__));

// Require composer autoload with error checking
$autoloadPath = PROJECT_ROOT . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    exit(
        <<<HTML
        <div style="text-align:center">
            <h1>Arquivo <code>vendor/autoload.php</code> não foi encontrado</h1>
            <span>Por favor execute <code>composer install</code> ou <code>composer dump-autoload</code> para gera-lo.</span>
        </div>
        HTML);
}

// Start app session (if necessary will be moved to a standalone class)
session_start();

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(PROJECT_ROOT);
$dotenv->load();
