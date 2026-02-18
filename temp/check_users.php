<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all();
echo "=== ALL USERS ===" . PHP_EOL;
foreach ($users as $u) {
    echo "ID: {$u->id} | Email: {$u->email} | Status: {$u->status} | Role: {$u->role}" . PHP_EOL;
}
echo "Total: " . $users->count() . PHP_EOL;
