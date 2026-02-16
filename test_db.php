<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $results = Illuminate\Support\Facades\DB::select('SELECT 1');
    echo "Connection successful!\n";
    print_r($results);
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
