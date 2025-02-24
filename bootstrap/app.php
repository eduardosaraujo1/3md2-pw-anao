<?php

// Define project root path (relative to bootstrap/app.php)
define('PROJECT_ROOT', dirname(__DIR__));

// Require composer autoload dependencies
require_once PROJECT_ROOT . '/vendor/autoload.php';

// Require function helpers for application
require_once PROJECT_ROOT . '/app/Framework/Support/Helpers.php';

// Start app session
session_start();

// Load env
$dotenv = Dotenv\Dotenv::createImmutable(PROJECT_ROOT);
$dotenv->load();
