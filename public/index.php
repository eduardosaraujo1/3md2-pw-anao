<?php declare(strict_types=1);

use App\Framework\Http\Kernel;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

// Require composer autoload dependencies
require '../vendor/autoload.php';

// create login session

// create request object
$request = Request::createFromGlobals();

// create http kernel
$kernel = new Kernel();

// use kernel to get response from request
$response = $kernel->handle($request);

// display the response to the user
$response->send();

// TODO: Tailwind (later)