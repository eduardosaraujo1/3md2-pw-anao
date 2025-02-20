<?php

namespace App\Framework\DTOs;

use FastRoute\Dispatcher;

class DispatchResult
{
    public function __construct(
        public int $status,
        public mixed $handler = null,
        /** @var array<string,string> */
        public array $params = [],
        /** @var array<string> */
        public array $allowedMethods = []
    ) {
    }

    /**
     * Creates DispatchResult object from the FastRoute ad hoc array output
     * @param array{int,callable,array<string,string>}|array{int,array<string>}|array{int} $output
     * @throws \InvalidArgumentException
     */
    public static function createFromFastRoute(array $output): DispatchResult
    {
        $status = $output[0];

        if ($status === Dispatcher::FOUND && count($output) === 3) {
            /** @var array{int,callable,array<string,string>} $output */
            return new self(
                status: $output[0],
                handler: $output[1],
                params: $output[2]
            );
        }

        if ($status === Dispatcher::METHOD_NOT_ALLOWED && count($output) === 2) {
            /** @var array{int,array<string>} $output */
            return new self(
                status: Dispatcher::METHOD_NOT_ALLOWED,
                allowedMethods: $output[1]
            );
        }

        if ($status === Dispatcher::NOT_FOUND && count($output) === 1) {
            /** @var array{int} $output */
            return new self(
                status: Dispatcher::NOT_FOUND,
            );
        }

        throw new \InvalidArgumentException("Invalid FastRoute array format: " . var_export($output, true), 1);
    }
}