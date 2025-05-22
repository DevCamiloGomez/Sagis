<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class SendMassEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $graduates;
    protected $subject;
    protected $content;
    public $tries = 3; // Número de intentos
    public $timeout = 300; // 5 minutos de timeout

    /**
     * Create a new job instance.
     */
    public function __construct($graduates, $subject, $content)
    {
        $this->graduates = $graduates;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Log::info('Iniciando job de envío masivo de correos', [
                'total_graduados' => $this->graduates->count(),
                'asunto' => $this->subject
            ]);

            // Configurar el transporte SMTP
            $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
            $transport->setUsername('sagisufps@gmail.com')
                     ->setPassword('iqdnejykmadgpkdd')
                     ->setTimeout(30);

            Log::info('Configuración del transporte SMTP', [
                'host' => $transport->getHost(),
                'port' => $transport->getPort(),
                'encryption' => $transport->getEncryption(),
                'username' => $transport->getUsername(),
                'timeout' => $transport->getTimeout()
            ]);

            $mailer = new Swift_Mailer($transport);
            $totalEnviados = 0;
            $totalErrores = 0;

            // Procesar los graduados en grupos más pequeños
            $chunkSize = 5;
            $chunks = $this->graduates->chunk($chunkSize);

            Log::info('Iniciando procesamiento por chunks', [
                'tamaño_chunk' => $chunkSize
            ]);

            foreach ($chunks as $chunk) {
                Log::info('Procesando nuevo chunk', [
                    'tamaño_chunk' => $chunk->count(),
                    'primer_graduado' => $chunk->first() ? [
                        'id' => $chunk->first()->id,
                        'email' => $chunk->first()->email,
                        'nombre' => $chunk->first()->name
                    ] : null
                ]);

                foreach ($chunk as $graduate) {
                    if (empty($graduate->email)) {
                        Log::warning('Graduado sin correo electrónico', [
                            'id' => $graduate->id,
                            'nombre' => $graduate->name
                        ]);
                        continue;
                    }

                    $intentos = 0;
                    $enviado = false;

                    while ($intentos < 3 && !$enviado) {
                        try {
                            $intentos++;
                            Log::info('Intento de envío', [
                                'email' => $graduate->email,
                                'intento' => $intentos
                            ]);

                            $message = new Swift_Message();
                            $message->setSubject($this->subject)
                                   ->setFrom(['sagisufps@gmail.com' => 'SAGIS UFPS'])
                                   ->setTo([$graduate->email => $graduate->name])
                                   ->setBody($this->content, 'text/html');

                            $result = $mailer->send($message);
                            
                            if ($result > 0) {
                                $totalEnviados++;
                                $enviado = true;
                                Log::info('Correo enviado exitosamente', [
                                    'email' => $graduate->email,
                                    'nombre' => $graduate->name,
                                    'intento' => $intentos
                                ]);
                            } else {
                                Log::error('Error al enviar correo', [
                                    'email' => $graduate->email,
                                    'nombre' => $graduate->name,
                                    'intento' => $intentos,
                                    'resultado' => $result
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Error en intento de envío', [
                                'email' => $graduate->email,
                                'error' => $e->getMessage(),
                                'intento' => $intentos
                            ]);

                            if ($intentos < 3) {
                                // Esperar antes de reintentar
                                sleep(2);
                            }
                        }
                    }

                    if (!$enviado) {
                        $totalErrores++;
                        Log::error('No se pudo enviar el correo después de 3 intentos', [
                            'email' => $graduate->email,
                            'nombre' => $graduate->name
                        ]);
                    }

                    // Pequeña pausa entre correos
                    usleep(200000); // 200ms de pausa
                }

                Log::info('Chunk procesado', [
                    'total_enviados_en_chunk' => $totalEnviados,
                    'total_errores_en_chunk' => $totalErrores
                ]);

                // Pausa entre chunks
                sleep(1);
            }

            Log::info('Resumen del envío masivo', [
                'total_enviados' => $totalEnviados,
                'total_errores' => $totalErrores
            ]);

        } catch (\Exception $e) {
            Log::error('Error en job de envío masivo de correos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Job de envío masivo falló', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
} 