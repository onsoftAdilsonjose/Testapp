<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class SendEmailMatricula extends Mailable
{
    use Queueable, SerializesModels;



    public $registrationData;

    public function __construct($registrationData)
    {
        $this->registrationData = $registrationData;
    }


    public function build()
    {
        // Generate the PDF using laravel-dompdf
        $pdf = PDF::loadView('emails.pdf_email', [$this->$registrationData]);
        return $this->subject('Purchase Confirmation')
            ->view('emails.pdf_email')
            ->attachData($pdf->output(), 'purchase_confirmation.pdf'); // Attach the PDF to the email
    }



}
