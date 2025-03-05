<?php declare(strict_types=1);

use Core\Http\Kernel;
use Core\Http\Request;

require "../bootstrap/app.php";

$request = Request::instance();
$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();