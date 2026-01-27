<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateAdminPassword extends Command
{
    protected $signature = 'admin:update-password {email} {password}';
    protected $description = 'Actualiza la contraseña de un administrador';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            $this->error("No se encontró un administrador con el email: {$email}");
            return 1;
        }

        // Actualizar la contraseña directamente en la base de datos para evitar el mutator
        DB::table('admins')
            ->where('email', $email)
            ->update(['password' => Hash::make($password)]);

        $this->info("Contraseña actualizada correctamente para el administrador: {$email}");
        return 0;
    }
} 