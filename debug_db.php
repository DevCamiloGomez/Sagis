<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\PostCategory;

echo "--- DB CONNECTION INFO ---\n";
echo "DB_CONNECTION: " . config('database.default') . "\n";
$config = config('database.connections.' . config('database.default'));
echo "DB_HOST: " . $config['host'] . "\n";
echo "DB_DATABASE: " . $config['database'] . "\n";
echo "DB_USERNAME: " . $config['username'] . "\n";

try {
    DB::connection()->getPdo();
    echo "PDO Connection: OK\n";
} catch (\Exception $e) {
    echo "PDO Connection: FAILED - " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n--- RECORD COUNTS ---\n";
echo "Admins: " . Admin::count() . "\n";
echo "PostCategories: " . PostCategory::count() . "\n";

echo "\n--- ADMIN RECORDS ---\n";
foreach (Admin::all() as $admin) {
    echo "ID: {$admin->id}, Email: {$admin->email}, PersonID: {$admin->person_id}\n";
}

echo "\n--- POST CATEGORIES ---\n";
foreach (PostCategory::all() as $cat) {
    echo "ID: {$cat->id}, Name: {$cat->name}\n";
}
