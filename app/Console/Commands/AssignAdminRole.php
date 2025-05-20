<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'admin:assign-role {email}';
    protected $description = 'Asigna el rol de administrador a un usuario por su email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $admin = Admin::where('email', $email)->first();
        if (!$admin) {
            $this->error("No se encontró un administrador con el email: {$email}");
            return 1;
        }

        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            $this->error('El rol "admin" no existe en la base de datos');
            return 1;
        }

        $admin->syncRoles([$role]);
        $this->info("Rol de administrador asignado exitosamente a {$email}");
        
        return 0;
    }
} 