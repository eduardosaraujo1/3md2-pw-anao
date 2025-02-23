<?php

namespace App\Framework\View;

class AttributeBag
{
    /**
     * @param array<string,mixed> $bag
     */
    public function __construct(
        public array $bag
    ) {
    }

    /**
     * Merge this attribute bag with default attributes from component
     * @param array $attributes
     */
    public function merge(array $attributes): string
    {
        $merged = $attributes;

        foreach ($this->bag as $key => $value) {
            if (isset($attributes[$key]) && $key === 'class') {
                // Concatenate class values with a space separator
                $merged[$key] = trim($attributes[$key] . ' ' . $value);
            } elseif (!isset($attributes[$key])) {
                // If key does not exist in $a, keep it from $b
                $merged[$key] = $value;
            }
            // Otherwise, keep $a[$key] (overwrite $b[$key])
        }

        $attributeFormat = self::attributesToString($merged);

        return $attributeFormat;
    }

    public static function attributesToString(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
        }

        return implode(' ', $html);
    }
}