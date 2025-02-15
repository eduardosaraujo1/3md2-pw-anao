<?php declare(strict_types=1);

use App\Framework\Http\Kernel;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

// Require composer autoload dependencies
require '../vendor/autoload.php';

// create login session

// create request object
$request = Request::createFromGlobals();

// create http kernel using get response

// display the response to the user
$content = 'Hello, world!';

$response = new Response(content: $content); // TODO: replace with Kernel
$response->send();

// for propper standards, view Gary CLarke's PHP Framework Pro

// TODO: Tailwind (later)