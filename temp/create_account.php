<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create or activate the user
$email = 'iamraymondbautista17@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    $user = User::create([
        'name' => 'Raymond Bautista',
        'email' => $email,
        'password' => Hash::make('Raymond@2026'),
        'status' => 'active',
        'role' => 'user',
        'account_type' => 'individual',
        'email_verified_at' => now(),
        'activated_at' => now(),
    ]);
    echo "Account CREATED and ACTIVATED!" . PHP_EOL;
} else {
    $user->update([
        'status' => 'active',
        'password' => Hash::make('Raymond@2026'),
        'activated_at' => now(),
        'email_verified_at' => now(),
    ]);
    echo "Account ACTIVATED!" . PHP_EOL;
}

echo "Email: {$user->email}" . PHP_EOL;
echo "Password: Raymond@2026" . PHP_EOL;
echo "Status: {$user->status}" . PHP_EOL;
echo "Role: {$user->role}" . PHP_EOL;
