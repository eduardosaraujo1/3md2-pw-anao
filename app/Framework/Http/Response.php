<?php

namespace App\Framework\Http;

class Response
{
    public function __construct(
        private ?string $content = '',
        private int $status = 200,
        private array $headers = []
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);
        echo $this->content;
    }
}
