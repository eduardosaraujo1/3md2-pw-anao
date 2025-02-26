<?php

namespace App\Framework\Database;

use App\Framework\Exception\Database\InvalidQueryException;
use App\Framework\Exception\Database\InvalidTableException;
use App\Framework\Exception\NotImplementedException;
use App\Framework\Exception\NullPropertyException;
use App\Framework\Facades\DB;

class Model
{
    /**
     * Creates model instance
     * @param string $query
     * @param array<string,mixed> $params
     * @return array<static>
     */
    public static function fromQuery(string $query, array $params = []): array
    {
        $result = DB::fetch($query, $params);

        try {
            return array_map(
                callback: fn(array $item): static => static::make($item),
                array: $result
            );
        } catch (NullPropertyException $th) {
            throw new InvalidQueryException(
                message: "SQL Query did not return parameters for constructing '" . static::class
                . "'. Please correct your SQL Statement: '$query' returned " . var_export($result, true)
            );
        }
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     */
    public static function make(array $params): mixed
    {
        throw new NotImplementedException("Method '" . static::class . "::make' does not exist");
    }
}