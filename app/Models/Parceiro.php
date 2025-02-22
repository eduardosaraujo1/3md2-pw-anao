<?php

namespace App\Models;

use App\Framework\Database\Model;
use App\Framework\Exception\NullPropertyException;

class Parceiro extends Model
{
    public function __construct(
        public int $id,
        public string $name,
        public string $contact,
        public bool $is_anao,
        public bool $is_gay,
        public int $id_anao,
    ) {
    }

    /**
     * @param array<string,mixed> $params
     * @throws \App\Framework\Exception\NullPropertyException
     */
    public static function make(array $params): Parceiro
    {
        if (
            !isset(
            $params['id'],
            $params['name'],
            $params['contact'],
            $params['is_anao'],
            $params['is_gay'],
            $params['id_anao'],
        )
        ) {
            throw new NullPropertyException("Missing property to make 'Anao': " . var_export($params, true));
        }

        return new Parceiro(
            id: $params['id'],
            name: $params['name'],
            contact: $params['contact'],
            is_anao: $params['is_anao'],
            is_gay: $params['is_gay'],
            id_anao: $params['id_anao'],
        );
    }
}