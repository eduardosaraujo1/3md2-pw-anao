<?php

namespace App\Framework\Http;

class Kernel
{
    public function __construct()
    {
    }

    public function handle(Request $request)
    {
        $content = 'Hello, world!';

        $response = new Response(content: $content); // TODO: replace with Kernel
        return $response;
    }
}
