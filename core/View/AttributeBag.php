<?php

namespace Core\View;

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
        // by default keep the classes specified in the component, merge it with the ones specified in the attribute bag later
        $result = $defaults;

        // by later I mean now
        foreach ($attributes as $name => $value) {
            if (isset($defaults[$name]) && $name === 'class') {
                // A custom class list was defined in this bag, let's merge it with the one specified in the component
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