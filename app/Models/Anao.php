<?php

namespace App\Models;

use App\Framework\Database\Model;
use App\Framework\Exception\NullPropertyException;

class Anao extends Model
{
    public function __construct(
        public int $id,
        public string $name,
        public int $age,
        public int $race,
        public float $height,
    ) {
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     */
    public static function make(array $params): Anao
    {
        if (
            !isset(
            $params['id'],
            $params['name'],
            $params['age'],
            $params['race'],
            $params['height'],
        )
        ) {
            throw new NullPropertyException("Missing property to make 'Anao': " . var_export($params, true));
        }
        return new Anao(
            id: $params['id'],
            name: $params['name'],
            age: $params['age'],
            race: $params['race'],
            height: $params['height'],
        );
    }
}