<?php
use App\Models\Admin;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = Admin::where('email', 'ingsistemas@ufps.edu.co')->first();
if ($admin) {
    // The model has a setPasswordAttribute mutator, so we provide the RAW password
    $admin->password = 'admin123'; 
    $admin->save();
    echo "SUCCESS: Password reset to 'admin123' (auto-hashed by mutator)\n";
} else {
    echo "NOT_FOUND\n";
}
