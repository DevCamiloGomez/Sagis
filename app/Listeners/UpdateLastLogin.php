<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class UpdateLastLogin
{
    /**
     * Actualiza el campo last_login_at cuando un usuario inicia sesión.
     */
    public function handle(Login $event): void
    {
        try {
            // Solo actualizamos usuarios del guard 'web' (graduados)
            if ($event->guard === 'web') {
                $event->user->update(['last_login_at' => now()]);
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar last_login_at', ['error' => $e->getMessage()]);
        }
    }
}
