<?php

use App\Framework\Database\Connection;

require __DIR__ . '/../../bootstrap/app.php';

// create connection
$conn = Connection::singleton();

// run each sql script in migrations
$dir = realpath(__DIR__ . '/../seeders');
$files = array_diff(scandir($dir), ['.', '..']); // Get sorted list

foreach ($files as $file) {
    // get file path
    $filePath = realpath("$dir/$file");

    // get file contents
    $content = file_get_contents($filePath);

    $conn->exec($content);
}

$conn->close();