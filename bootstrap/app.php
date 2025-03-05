<?php

use Core\Session;

const PROJECT_ROOT = __DIR__ . '/..';

// Require composer autoload
try {
    require_once PROJECT_ROOT . '/vendor/autoload.php';
} catch (\Throwable) {
    exit(
        <<<HTML
        <div style="text-align:center">
            <h1>Arquivo <code>vendor/autoload.php</code> não foi encontrado</h1>
            <span>Por favor execute <code>composer install</code> ou <code>composer dump-autoload</code> para gera-lo.</span>
        </div>
        HTML
    );
}

// Load generic core functions
require_once PROJECT_ROOT . '/core/functions.php';

// Read .env file
try {
    \Dotenv\Dotenv::createImmutable(PROJECT_ROOT)->load();
} catch (\Throwable) {
    exit(
        <<<HTML
        <div style="text-align:center">
            <h1>Arquivo <code>.env</code> não foi encontrado</h1>
            <span>Por favor copie o arquivo <code>.env.example</code> e renomeie-o para <code>.env</code></span>
        </div>
        HTML
    );
}

// Start PHP Session
Session::start();
