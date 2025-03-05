<?php

namespace Core\Http;

class Response
{
    public function __construct(
        private ?string $content = '',
        private int $status = 200,
        /** @var array<string> */
        private array $headers = []
    ) {
        $this->headers = array_merge(headers_list(), $headers);
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

    public function header(string $header)
    {
        $this->headers[] = $header;

        return $this;
    }

    public function status(): int
    {
        return $this->status;
    }
}
