<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\PDF; // Import PDF namespace

class SendMonthlyReport extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Rename it to a more appropriate name, e.g., $mailData
    public $pdf;

    public function __construct($data, $pdf)
    {
        $this->data = $data;
        $this->pdf = $pdf;
    }

    public function build()
    {
        $student = $this->data['student']; // Extract student data

        $subject = 'Monthly Report for ' . $student['primeiro_nome'] . ' ' . $student['ultimo_nome'];

        $pdfContent = $this->pdf->output(); // Generate the PDF content

        return $this->view('emails.pdf_email')
            ->subject($subject)
            ->attachData($pdfContent, 'report.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
