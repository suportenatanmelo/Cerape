<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
var_export([
    'broadcast_default' => config('broadcasting.default'),
    'broadcast_env' => env('BROADCAST_CONNECTION'),
    'pusher_key' => env('PUSHER_APP_KEY'),
]);
