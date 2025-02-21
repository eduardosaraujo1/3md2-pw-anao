<?php

namespace App\Framework\Http;

class Response
{
    public function __construct(
        private ?string $content = '',
        private int $status = 200,
        /** @var array<string> */
        private array $headers = []
    ) {
        $this->headers = headers_list();
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function send(): void
    {
        // set http status
        http_response_code($this->status);

        // redefine headers
        header_remove();
        foreach ($this->headers as $header) {
            header($header);
        }

        // show content
        echo $this->content;
    }
}
