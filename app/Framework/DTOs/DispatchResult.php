<?php

namespace App\Framework\DTOs;

use FastRoute\Dispatcher;

class DispatchResult
{
    public function __construct(
        public int $status,
        public mixed $handler = null,
        public ?array $params = null,
        public ?array $allowedMethods = null
    ) {
    }

    /**
     * Creates DispatchResult object from the FastRoute ad hoc array output
     * @throws \InvalidArgumentException
     */
    public static function createFromFastRoute(array $fastRouteResult): DispatchResult
    {
        $status = $fastRouteResult[0];

        return match ($status) {
            Dispatcher::FOUND => new self(
                status: Dispatcher::FOUND,
                handler: $fastRouteResult[1],
                params: $fastRouteResult[2]
            ),

            Dispatcher::METHOD_NOT_ALLOWED => new self(
                status: Dispatcher::METHOD_NOT_ALLOWED,
                allowedMethods: $fastRouteResult[1]
            ),

            Dispatcher::NOT_FOUND => new self(
                status: Dispatcher::NOT_FOUND,
            ),

            default => throw new \InvalidArgumentException("Invalid FastRoute array format: " . var_export($fastRouteResult, true), 1)
        };
    }
}