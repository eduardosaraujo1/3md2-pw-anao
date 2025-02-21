<?php

namespace App\Framework\Database;

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

        return array_map(function (array $item) {
            return static::make($item);
        }, $result);
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     * @return static
     */
    public static function make(array $params): static
    {
        throw new \Exception("Not Implemented Method: 'Model::make'");
    }
}