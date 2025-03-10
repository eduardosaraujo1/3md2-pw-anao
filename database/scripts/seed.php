<?php

use Core\Database\Connection;

require __DIR__ . '/../../bootstrap/app.php';

// create connection
$conn = Connection::createFromEnv(true);

// run each sql script in migrations
$dir = realpath(PROJECT_ROOT . '/database/seeders');

if (!$dir) {
    return "File {PROJECT_ROOT}/database/seeders not found";
}

$files = array_diff(scandir($dir), ['.', '..']); // Get sorted list

foreach ($files as $file) {
    // get file path
    $filePath = realpath("$dir/$file");

    // get file contents
    $content = file_get_contents($filePath);

    $conn->exec($content);
}

$conn->close();