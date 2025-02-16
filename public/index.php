<?php declare(strict_types=1);
// Front-Controller Pattern implementaiton inspired by

use App\Framework\Http\Kernel;
use App\Framework\Http\Request;

// Require composer autoload dependencies
require '../vendor/autoload.php';

// Require function helpers for application
require '../app/Framework/Helpers/helpers.php';

// create request object
$request = Request::createFromGlobals();

// create http kernel
$kernel = new Kernel();

// use kernel to get response from request
$response = $kernel->handle($request);

// display the response to the user
$response->send();

// TODO: Tailwind (later)