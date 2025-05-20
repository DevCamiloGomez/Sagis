<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPassword extends Command
{
    protected $signature = 'admin:update-password {email} {password?}';
    protected $description = 'Actualiza la contraseña de un administrador';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password') ?? 'password';
        
        $admin = Admin::where('email', $email)->first();
        if (!$admin) {
            $this->error("No se encontró un administrador con el email: {$email}");
            return 1;
        }

        $admin->password = $password;
        $admin->save();

        $this->info("Contraseña actualizada exitosamente para {$email}");
        return 0;
    }
} 