<?php

// Require composer autoload dependencies
require __DIR__ . '/../vendor/autoload.php';

// Require function helpers for application
require __DIR__ . '/../app/Framework/Support/Helpers.php';

// Start app session
session_start();

// Load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();