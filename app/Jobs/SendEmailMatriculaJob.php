<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailMatricula;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Add this import statement

class SendEmailMatriculaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $registrationData;

    public function __construct($registrationData)
    {
        $this->registrationData = $registrationData;
    }

    public function handle()
    {
        try {
            // Logic to send purchase confirmation email
            Mail::to('adilson2012jose@gmail.com')->send(new SendEmailMatricula($this->registrationData));
            Log::info('Email sent');
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}
