<?php

namespace App\Framework\Database;

use App\Framework\Facades\DB;

abstract class Model
{
    protected static string $table;

    public static function all(): array
    {
        return DB::fetch("SELECT * FROM " . static::$table);
    }

    
    /**
     * @param array<string,mixed> $data
     */
    public static function create(array $data): self {
        throw new \Exception("Method not implemented", 1);
    }
}