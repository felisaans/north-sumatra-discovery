<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Mode perawatan / Maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader Composer / Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Jalankan aplikasi / Bootstrap Laravel and handle the request
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
