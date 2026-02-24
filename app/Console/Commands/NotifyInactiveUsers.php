<?php

namespace App\Console\Commands;

use App\Mail\InactiveUserReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifyInactiveUsers extends Command
{
    protected $signature   = 'app:notify-inactive-users';
    protected $description = 'Envía un correo de recordatorio a graduados inactivos por más de 30 días.';

    public function handle(): int
    {
        $cutoff = now()->subDays(30);

        $inactivos = User::whereHas('roles', fn ($q) => $q->where('name', 'graduado'))
            ->where(function ($q) use ($cutoff) {
                $q->whereNull('last_login_at')
                  ->orWhere('last_login_at', '<', $cutoff);
            })
            ->with('person')
            ->get();

        if ($inactivos->isEmpty()) {
            $this->info('No hay graduados inactivos en este momento.');
            return self::SUCCESS;
        }

        $enviados = 0;
        $errores  = 0;

        foreach ($inactivos as $user) {
            if (!$user->person || !$user->email) {
                continue;
            }

            try {
                Mail::to($user->email)->send(new InactiveUserReminder($user->person));
                $enviados++;
                Log::info("Recordatorio enviado a {$user->email}");
            } catch (\Exception $e) {
                $errores++;
                Log::error("Error enviando recordatorio a {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("✅ Recordatorios enviados: {$enviados} | Errores: {$errores}");
        return self::SUCCESS;
    }
}
