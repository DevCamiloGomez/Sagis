<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify SMTP configuration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Attempting to send test email to: $email");

        try {
            Mail::raw('This is a test email from the server.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('SMTP Test Email');
            });

            $this->info('Email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email.');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
