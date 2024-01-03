<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use PDF; // Import the PDF class
use App\Mail\RegistrationEmail;

class RegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $purchaseDetails;

    public function __construct($user, $purchaseDetails)
    {
        $this->user = $user;
        $this->purchaseDetails = $purchaseDetails;
    }

    public function handle()
    {
        try {
            // Logic to send purchase confirmation email
            Mail::to('adilson2012jose@gmail.com')->send(new RegistrationEmail($this->user, $this->purchaseDetails));
            Log::info('Email sent');
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}
