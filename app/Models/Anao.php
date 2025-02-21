<?php

namespace App\Models;

use App\Framework\Database\Model;

class Anao extends Model
{
    public function __construct(
        public int $id,
        public string $name,
        public int $age,
        public int $race,
        public float $height,
        public bool $is_gay,
    ) {
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     * @return static
     */
    public static function make(array $params): static
    {
        return new Anao(
            id: $params['id'],
            name: $params['name'],
            age: $params['age'],
            race: $params['race'],
            height: $params['height'],
            is_gay: $params['is_gay'],
        );
    }
}