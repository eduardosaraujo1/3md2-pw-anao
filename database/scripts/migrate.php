<?php

use Core\Database\Connection;

require __DIR__ . '/../../bootstrap/app.php';

// create connection
$conn = Connection::createFromEnv(false);

// reset database
$conn->exec('
DROP SCHEMA IF EXISTS db_anoes;
CREATE SCHEMA db_anoes;
use db_anoes;
');

// run each sql script in migrations
$dir = realpath(__DIR__ . '/../migrations');
$files = array_diff(scandir($dir), ['.', '..']); // Get sorted list

foreach ($files as $file) {
    // get file path
    $filePath = realpath("$dir/$file");

    // get file contents
    $content = file_get_contents($filePath);

    $conn->exec($content);
}

$conn->close();