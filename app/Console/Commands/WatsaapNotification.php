<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\DB;
use App\MyCustomFuctions\Notification;

class WatsaapNotification extends Command
{
    protected $signature = 'notifications:send-sms-by-watsaap';
    protected $description = 'Send SMS notifications to users using Twilio';

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
    try {
        $sid = config("services.twilio.sid");
        $token = config("services.twilio.token");
        $whatsappNumber ='+14155238886"'; // Your Twilio WhatsApp number

        $twilio = new Client($sid, $token);

        $successMessages = [];
        $failedMessages = [];

             $studentIDs = DB::table('users')
            ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->select('users.id', 'telefoneAlternativo', 'primeiro_nome', 'ultimo_nome','email','mensalidade.Classe_id')
            ->get();





        $anolectivoID = DB::table('ano_lectivos')->select('id')->first();
        $studentData = Notification::Devedores($studentIDs, $anolectivoID->id);

        foreach ($studentData as $student) {
            $phoneNumber = $student['telefoneAlternativo'];
            $lastPrimeiroNome = $student['primeiro_nome'];
            $lastUltimoNome = $student['ultimo_nome'];
            $percetagem  =$student['percetagem'];
            $TotalApagar = $student['TotalApagar'];
            $PagamentoMensal = $student['PagamentoMensal'];
            $NumeroDeMeses = $student['NumeroDeMeses'];
            $TotalMulta  =$student['TotalMulta'];
            $fullName = $lastPrimeiroNome . ' ' . $lastUltimoNome;
            $Total = $TotalApagar+$TotalMulta;
            $informacaoescola = DB::table('informacaoescola')->select('nomeDaempresa','telefoneAlternativo','numeroDotelefone','email')->first();
            $mesNomes = [];






            foreach ($student['mesData'] as $mes) {
                $lastMesNome = $mes['mesNome'];
                $mesNomes[] = $lastMesNome;
            }

            $mesNomesString = implode(', ', $mesNomes);

            if (!empty($phoneNumber)) {
                try {
                    // Send WhatsApp message
                    // $message = $twilio->messages->create(
                    //     "whatsapp:$phoneNumber", // WhatsApp number with 'whatsapp:' prefix
                    //     [
                    //         "from" => "whatsapp:$whatsappNumber", // Your Twilio WhatsApp number
                    //         "body" => "Olá, $fullName. Este é um lembrete amigável de que seu pagamento de 20.000 mil kZ para os meses: $mesNomesString vence em [data]. Clique aqui para pagar: www.onsoft.com",
                    //     ]
                    // );



                                    $message = $twilio->messages->create(
                    "whatsapp:+244926551976", // to
                    [
                                 "from" => "whatsapp:+14155238886",
                       
                                  "body" => "Prezado $fullName,
                        Esperamos que esta mensagem o encontre bem. Gostaríamos de 
                        informá-lo sobre a falta de pagamento de um determinado mês
                         em sua conta. Seu saldo devedor é o seguinte:
                        Valor sem penalidade de $percetagem por cento:  $TotalApagar Kz 
                        Valor com penalidade de $percetagem por cento:  $Total Kz
                        Número de meses a pagar: $NumeroDeMeses
                        Confira abaixo o detalhamento dos meses devidos e seus respectivos valores:
                        $mesNomesString,
                        Certifique-se de liquidar o saldo devedor o mais rápido possível para 
                        evitar novas penalidades.Se você tiver alguma dúvida ou precisar de ajuda, 
                        sinta-se à vontade para entrar em contato com nossa equipe de suporte ao cliente 
                        Contacto Telefonico $informacaoescola->telefoneAlternativo ou  
                        $informacaoescola->numeroDotelefone ,tambem por   $informacaoescola->email
                        Obrigado por sua pronta atenção a este assunto.
                        Sinceramente,
                        $informacaoescola->nomeDaempresa.",
                        // "mediaUrl" => ["https://images.unsplash.com/photo-1545093149-618ce3bcf49d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80"],
                        // 'mediaUrl' => asset('storage/pdfs/filename.pdf') // Adjust the path if needed
                    ]
                );

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
