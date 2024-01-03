<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MyCustomFuctions\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMonthlyReport;

use Illuminate\Support\Facades\DB;
use PDF;


class SendMonthlyReports extends Command
{
    protected $signature = 'email:send-monthly-reports';
    protected $description = 'Send monthly reports to students';

    public function handle()
    {
        // Retrieve student IDs and relevant data
        $studentIDs = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereIn('roles.id', [4, 48]) // Fetch users with roles 4 or 48
            ->where('users.id', 48) // Fetch users with roles 4 or 48
            ->select('users.id', 'telefoneAlternativo', 'primeiro_nome', 'ultimo_nome', 'email')
            ->get();

        $anolectivoID = DB::table('ano_lectivos')->select('id')->first();
        $studentData = Notification::Devedores($studentIDs, $anolectivoID->id);

        // Loop through each student and send email
        foreach ($studentData as $student) {
            if (!empty($student['email'])) {
                try {
                    // Prepare data to send along with the email
                    $dataToSend = [
                        'student' => $student,
                        'mesData' => $student['mesData'], // Include mesData in the data to send
                    ];

                    $pdf = PDF::loadView('emails.pdf_email', ['student' => $dataToSend]);

                    // Send email with PDF attachment
                    Mail::to($student['email'])->send(new SendMonthlyReport($dataToSend, $pdf));
                    $this->info('Email sent to: ' . $student['email']);
                } catch (\Exception $e) {
                    $this->error('Failed to send email to: ' . $student['email']);
                }
            } else {
                $this->info('Skipped sending email to student with empty email.');
            }
        }

        $this->info('All emails sent.');
    }
}
