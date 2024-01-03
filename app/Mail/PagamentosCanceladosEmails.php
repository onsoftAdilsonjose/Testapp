<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF; // Import the PDF class
class PagamentosCanceladosEmails extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $purchaseDetails;

    public function __construct($user, $purchaseDetails)
    {
        $this->user = $user;
        $this->purchaseDetails = $purchaseDetails;
    }

    public function build()
    {
        // Generate the PDF using laravel-dompdf
        $pdf = PDF::loadView('emails.pdf_email', ['user' => $this->user, 'purchaseDetails' => $this->purchaseDetails]);

        return $this->subject('Purchase Confirmation')
            ->view('emails.pdf_email')
            ->attachData($pdf->output(), 'purchase_confirmation.pdf'); // Attach the PDF to the email
    }
}
