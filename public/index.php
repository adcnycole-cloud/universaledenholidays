<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ===== ERROR LOGGING FOR DEBUGGING =====
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$errorLogFile = __DIR__.'/error_log.txt';

// Set custom error log file
ini_set('error_log', $errorLogFile);

// Catch fatal errors
register_shutdown_function(function () use ($errorLogFile) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $msg = "[".date('Y-m-d H:i:s')."] FATAL: {$error['message']} in {$error['file']} on line {$error['line']}\n";
        file_put_contents($errorLogFile, $msg, FILE_APPEND);
    }
});

// Catch exceptions
set_exception_handler(function ($e) use ($errorLogFile) {
    $msg = "[".date('Y-m-d H:i:s')."] EXCEPTION: ".$e->getMessage()." in ".$e->getFile()." on line ".$e->getLine()."\n".$e->getTraceAsString()."\n";
    file_put_contents($errorLogFile, $msg, FILE_APPEND);
    http_response_code(500);
    echo '<h1>500 Internal Server Error</h1><p>Check <a href="error_log.txt">error_log.txt</a> for details.</p>';
    exit;
});
// =======================================

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
try {
    require __DIR__.'/../vendor/autoload.php';
} catch (Throwable $e) {
    file_put_contents($errorLogFile, "[".date('Y-m-d H:i:s')."] Vendor autoload failed: ".$e->getMessage()."\n", FILE_APPEND);
    http_response_code(500);
    echo '<h1>500 - Vendor folder missing!</h1><p>Run <strong>composer install</strong> on the server. Check <a href="error_log.txt">error_log.txt</a>.</p>';
    exit;
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
