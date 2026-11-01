<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Request Logger Enabled
    |--------------------------------------------------------------------------
    |
    | Master toggle to enable or disable request logging across the app.
    |
    */
    'enabled' => env('REQUEST_LOGGER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Console / Terminal Output
    |--------------------------------------------------------------------------
    |
    | When enabled, formatted colored logs will be printed directly to stdout
    | so they appear in real-time when running "php artisan serve" or CLI server.
    |
    */
    'console' => env('REQUEST_LOGGER_CONSOLE', true),

    /*
    |--------------------------------------------------------------------------
    | Application Log File
    |--------------------------------------------------------------------------
    |
    | When enabled, logs are also written to Laravel's configured logging system.
    |
    */
    'log_file' => env('REQUEST_LOGGER_FILE', true),

    /*
    |--------------------------------------------------------------------------
    | Log Channel
    |--------------------------------------------------------------------------
    |
    | Specify a custom log channel or leave null to use the default channel.
    |
    */
    'channel' => env('REQUEST_LOGGER_CHANNEL', null),

    /*
    |--------------------------------------------------------------------------
    | Colorized Terminal Output
    |--------------------------------------------------------------------------
    |
    | Enable or disable ANSI colors in terminal console output.
    |
    */
    'colors' => env('REQUEST_LOGGER_COLORS', true),

    /*
    |--------------------------------------------------------------------------
    | Paths to Ignore
    |--------------------------------------------------------------------------
    |
    | List of request path patterns that should be skipped by the logger.
    |
    */
    'ignore_paths' => [
        'favicon.ico',
        '*.ico',
        'up',
    ],

    /*
    |--------------------------------------------------------------------------
    | Include Authenticated User
    |--------------------------------------------------------------------------
    |
    | When true, displays user ID or email if the request is authenticated.
    |
    */
    'include_user' => env('REQUEST_LOGGER_INCLUDE_USER', true),
];
