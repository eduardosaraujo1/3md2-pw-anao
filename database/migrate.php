<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Framework\Database\Connection;

$migrateScript = file_get_contents(__DIR__ . '/create_script.sql');

if ($migrateScript) {
    Connection::singleton()->exec($migrateScript);
}