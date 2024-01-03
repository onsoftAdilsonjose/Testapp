<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\DB;
use App\MyCustomFuctions\Notification;


class SendSmsNotifications extends Command
{
    protected $signature = 'notifications:send-sms';
    protected $description = 'Send SMS notifications to users using Twilio';

public function handle()
{
    try {
        $sid = config("services.twilio.sid");
        $token = config("services.twilio.token");
        $sendernumber = config("services.twilio.phone_number");
        $twilio = new Client($sid, $token);
        
        $successMessages = [];
        $failedMessages = [];

        $studentIDs = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.id', '=', 4)
            ->select('users.id', 'telefoneAlternativo', 'primeiro_nome', 'ultimo_nome','email')
            ->get();

        $anolectivoID = DB::table('ano_lectivos')->select('id')->first();
        $studentData = Notification::Devedores($studentIDs, $anolectivoID->id);

        foreach ($studentData as $student) {
            $phoneNumber = $student['telefoneAlternativo'];
            $lastPrimeiroNome = $student['primeiro_nome'];
            $lastUltimoNome = $student['ultimo_nome'];

            $fullName = $lastPrimeiroNome . ' ' . $lastUltimoNome;

            $mesNomes = [];

            foreach ($student['mesData'] as $mes) {
                $lastMesNome = $mes['mesNome'];
                $mesNomes[] = $lastMesNome;
            }

            $mesNomesString = implode(', ', $mesNomes);

            if (!empty($phoneNumber)) {
                try {
                    $message = $twilio->messages->create($phoneNumber, [
                        "body" => "Olá, $fullName. Este é um lembrete amigável de que seu pagamento de 20.000 mil kZ para os meses: $mesNomesString vence em [data]. Clique aqui para pagar: www.onsoft.com",
                        "from" => $sendernumber
                    ]);

                    $successMessages[] = "Mensagem enviada para $fullName ($phoneNumber) com sucesso nos meses: $mesNomesString!";
                } catch (TwilioException $e) {
                    $failedMessages[] = "Falha no envio para $fullName ($phoneNumber) nos meses: $mesNomesString. Erro: " . $e->getMessage();
                }
            } else {
                $failedMessages[] = "Número de telefone ausente para $fullName. Ignorando.";
            }
        }

        foreach ($successMessages as $successMessage) {
            $this->info($successMessage);
        }

        foreach ($failedMessages as $failedMessage) {
            $this->error($failedMessage);
        }

        if (empty($failedMessages)) {
            $this->info('Todas as mensagens foram enviadas com sucesso.');
        } else {
            $this->error('Algumas mensagens falharam. Verifique os logs para detalhes.');
        }
    } catch (TwilioException $e) {
        $this->error('Erro ao se conectar com o serviço Twilio.');
        \Log::error('Twilio Exception: ' . $e->getMessage());
    }
}

}
