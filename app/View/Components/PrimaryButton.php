<?php

use App\Framework\Exception\NotImplementedException;

if (!function_exists('primary_button')) {
    function primary_button(string $content = '', ?string $href, ?array $classList)
    {
        throw new NotImplementedException("Missing PrimaryButton implementation");
    }
}