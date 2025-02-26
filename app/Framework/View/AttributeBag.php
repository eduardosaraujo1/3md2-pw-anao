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
     * @param array $defaults the default attributes to use if none are set in the bag
     */
    public function merge(array $defaults): string
    {
        $attributes = $this->bag;
        $result = $defaults;

        foreach ($attributes as $name => $value) {
            if (isset($defaults[$name]) && $name === 'class') {
                // The component default includes a class to merge
                $result[$name] = trim($defaults[$name] . ' ' . $value);
            } else {
                // Override the component defaults with the user defined attribute bag
                $result[$name] = $value;
            }
        }

        // convert format from assoc array to html
        $attributeFormat = self::attributesToString($result);

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