<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Serve static assets directly
if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

// Ignore favicon so it doesn't clutter terminal logs
if ($uri === '/favicon.ico' || str_ends_with($uri, '.ico')) {
    return false;
}

require_once $publicPath.'/index.php';
