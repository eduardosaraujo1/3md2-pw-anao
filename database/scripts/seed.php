<?php

use App\Framework\Database\Connection;

require __DIR__ . '/../../bootstrap/app.php';

// create connection
$conn = Connection::singleton();

// run each sql script in seeders
$dir = realpath(__DIR__ . '/../seeders');
$iterator = new DirectoryIterator($dir);

foreach ($iterator as $file) {
    if (!$file->isDot()) {
        $filecontents = file_get_contents($file->getPathName());
        $conn->exec($filecontents);
    }
}

$conn->close();