<?php declare(strict_types=1);

use App\Framework\Http\Kernel;
use App\Framework\Http\Request;

// Bootstrap app
require "../bootstrap/app.php";

// create request object
$request = Request::createFromGlobals();

// create http kernel
$kernel = new Kernel();

// use kernel to get response from request
$response = $kernel->handle($request);

// display the response to the user
$response->send();