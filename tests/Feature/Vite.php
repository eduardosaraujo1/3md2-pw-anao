<?php

test('for vite plugin resolution', function () {
    $cssPath = 'resources/css/app.css';
    $jsPath = 'resources/js/app.js';

    $response = vite([$cssPath, $jsPath]);

    // Check that the response contains a valid link tag
    expect(str_contains($response, '<link'))->toBeTrue();

    $isDevMode = str_contains($response, $_ENV['VITE_PORT'] ?? '5173');
    $hotFileExists = file_exists(base_path('public/hot'));

    if ($isDevMode) {
        // In development mode, the hot file should exist.
        expect($hotFileExists)->toBeTrue();
    } else {
        // In build mode, the hot file should NOT exist.
        expect($hotFileExists)->toBeFalse();
    }
});
