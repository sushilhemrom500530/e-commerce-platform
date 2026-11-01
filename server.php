<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Immediately respond to favicon requests to prevent blocking the single-threaded PHP server
if ($uri === '/favicon.ico' || str_ends_with($uri, '.ico')) {
    http_response_code(204);
    exit;
}

// Serve static assets directly
if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
