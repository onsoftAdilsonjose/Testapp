<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;
use DB;
use Illuminate\Support\Facades\Log;

class SendSmsMatriculaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $RegistrationEstudante;

    public function __construct($RegistrationEstudante)
    {
        $this->RegistrationEstudante = $RegistrationEstudante;
    }

    public function handle()
    {



        $estudanteData = $this->RegistrationEstudante['estudante'];
        $dadosRelacionados = $this->RegistrationEstudante['DadosRelacionados'];
        $mensalidadeData = $this->RegistrationEstudante['Mensalidade'];

        $primeiroNome = $estudanteData->primeiro_nome;
        $ultimoNome = $estudanteData->ultimo_nome;
        $EstudanteregNumero = $estudanteData->reg_Numero;
        $nomeCompleto = $primeiroNome . ' ' . $ultimoNome;
        // Now you can use $estudanteData, $dadosRelacionados, and $mensalidadeData as needed.


        $MatriculaPreco   = $mensalidadeData->MatriculaPreco;
        $classe_name = $mensalidadeData->classe_name;
        $nomePeriodo = $mensalidadeData->nomePeriodo;
        $ano_lectivo = $mensalidadeData->ano_lectivo;
        $nomeCurso = $mensalidadeData->nomeCurso;
        $informacaoescola = DB::table('informacaoescola')->select('nomeDaempresa','telefoneAlternativo','numeroDotelefone','email')->first();

        $Messagens = "Prezado $nomeCompleto,
        Temos o prazer de informar que sua inscrição foi concluída com sucesso para o ano acadêmico de $ano_lectivo no $informacaoescola->nomeDaempresa.
        Detalhes de registro:
        Classe: $classe_name
        Curso: $nomeCurso
        Taxa de inscrição: $MatriculaPreco Kz
        Ano Letivo: $ano_lectivo
        Número de registro: $EstudanteregNumero
        Sua dedicação à sua educação é muito apreciada e estamos ansiosos para recebê-lo em nosso Colegio.
        Se você tiver alguma dúvida ou precisar de mais assistência, não hesite em entrar em contato com nosso Departamento de Pedagogia em $informacaoescola->email, número de telefone: $informacaoescola->numeroDotelefone, telefone alternativo: $informacaoescola->telefoneAlternativo.
        Desejamos a você uma jornada acadêmica de sucesso e gratificante pela frente!
        Atenciosamente,
        $informacaoescola->nomeDaempresa";





  
       

        



        try {
            $twilioSid = config("services.twilio.sid");
            $twilioToken = config("services.twilio.token");

            // Initialize Twilio client
            $twilio = new Client($twilioSid, $twilioToken);

            // WhatsApp number to send to
            $to = "whatsapp:+244926551976";
            // Twilio WhatsApp Sandbox number (replace with your Twilio number)
            $from = "whatsapp:+14155238886";
            
            $message = $twilio->messages->create($to, [
                "from" => $from,
                "body" => $Messagens,
            ]);

            if ($message->sid) {
                Log::info('WhatsApp message sent successfully');
            } else {
                Log::error('Failed to send WhatsApp message');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}
