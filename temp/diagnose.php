<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DB CONNECTION ===" . PHP_EOL;
echo "Driver: " . config('database.default') . PHP_EOL;
echo "Host: " . config('database.connections.pgsql.host') . PHP_EOL;
echo "Port: " . config('database.connections.pgsql.port') . PHP_EOL;
echo "Database: " . config('database.connections.pgsql.database') . PHP_EOL;

echo PHP_EOL . "=== USERS ===" . PHP_EOL;
echo "Total: " . App\Models\User::count() . PHP_EOL;
echo "Active: " . App\Models\User::where('status', 'active')->count() . PHP_EOL;
echo "Pending: " . App\Models\User::where('status', 'pending')->count() . PHP_EOL;

echo PHP_EOL . "=== DOCUMENTS ===" . PHP_EOL;
echo "Total: " . App\Models\Document::count() . PHP_EOL;

echo PHP_EOL . "=== MAIL CONFIG ===" . PHP_EOL;
echo "Mailer: " . config('mail.default') . PHP_EOL;
echo "Host: " . config('mail.mailers.smtp.host') . PHP_EOL;
echo "Port: " . config('mail.mailers.smtp.port') . PHP_EOL;

echo PHP_EOL . "=== TABLES ===" . PHP_EOL;
$tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
foreach ($tables as $t) {
    echo "  - " . $t->tablename . PHP_EOL;
}
