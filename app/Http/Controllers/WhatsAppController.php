<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;
use App\MyCustomFuctions\Pagamento;
use PDF;

class WhatsAppController extends Controller
{
    //





    private $twilioClient;

public function __construct()
{
    $this->twilioClient = new Client(config('services.twilio.sid'), config('services.twilio.token'));
}








        public function handle(Request $request)

    {









        // Get the incoming request from Twilio
        $data = $request->all();

        // Extract the message body from the request
        $body = $data['Body'];
        $sender =   $whatsappNumber ='+14155238886"'; // Your Twilio WhatsApp number

        // Process the incoming message and generate a response
        $responseMessage = $this->processIncomingMessage($body, $sender);

        // Create a TwiML response to send as a reply
        $response = new MessagingResponse();
        $response->message($responseMessage);

        return response($response, 200)->header('Content-Type', 'text/xml');
    }




     private function processIncomingMessage($body, $sender)
    {
        // Trim and convert the incoming message to lowercase for easier comparison
        $body = strtolower(trim($body));

        switch ($body) {
            case '1':
return'Boa noite Senhor *Adilson Miguel*
Cargo: *Estudante*
Ano Letivo: *2023/2024*
Curso: *Sem Curso*
Classe: *7ª Classe*
Disciplinas:
    - Língua Portuguesa = *18*
    - Inglês = *18*
    - Matemática = *18*
    - Biologia = *18*
    - Física = *18*
    - Química = *18*
    - Geografia = *18*
    - História = *18*
    - Educação Física = *18*
    - Ed. Moral e Cívica = *18*
    - Ed. Visual e Plástica = *18*
    - Ed. Laboral = *18*
STATUS: *Aprovado*'
;
            case '2':
                return 'Olá *Adilson Miguel*,

1. Número da fatura/transação: 5454456465
2. Data de Pagamento: 12/12/2024
3. Valor do Pagamento:1000 Kz 
4. Método de pagamento utilizado:
5. Quaisquer referências ou notas adicionais:Dienheiro

Ultima Factura Paga no Colegio.

Obrigado pela sua cooperação.

Atenciosamente,
Colegio Onsoft';
            case '3':
                return 'Olá Adilson Miguel ,

eu espero que você esteja bem. Gostaria de fornecer uma atualização sobre seu saldo atual e os pagamentos restantes da *Proprina Mensal*. Aqui estão os detalhes:

- Valor total devido: 20000 Kz 
- Número de pagamentos restantes: 2
- Pagamento Mensal com Acréscimo de 10%: Para Proprinas Atrasada

Lista dos próximos meses de pagamento com aumento de 10%:
1. Junho: Pago
2. Julho: Pago
3. Agosto: 10.000 Kz
4. Setembro: 10.00 Kz


Observe que os valores de pagamento aumentados entrarão em vigor a partir de Janeiro 2 . Se você tiver alguma dúvida ou precisar de mais esclarecimentos, não hesite em entrar em contato.

Obrigado pela sua cooperação contínua.

Atenciosamente,
Colegio onsoft';

            default:
                return 'Olá, obrigado por aceitar receber atualizações por SMS das Para Consultar Notas e 
Consultar Dados de Pagmento , Você pode enviar uma mensagem de texto
1-Consultar Notas
2-Consultar Dados de Pagmento
3-Consultar Dividas
a qualquer momento para Consultar.';
        }
    }




}
