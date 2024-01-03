<?php

namespace App\MyCustomFuctions;

use NumberToWords\NumberToWords;
use DB;
use App\Models\Transactions;
use App\Models\Meses;
use App\Models\classes;
use Carbon\Carbon;
use App\Models\Role;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Http\JsonResponse;
use PDF;
use App\Mail\SendPDFEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\EstudanteSaldo;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
class Notification
{
   
public static function PropinaReminder($variable, $phoneNumbers)
{
    try {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $sendernumber = getenv("TWILIO_PHONE_NUMBER");
        $twilio = new Client($sid, $token);
        
        $successMessages = [];

        foreach ($phoneNumbers as $phoneNumber) {
            $message = $twilio->messages->create($phoneNumber, [
                "body" => "Obrigado por ser Matriculado no Colegio Y! Sua conta foi criada com sucesso. Sua senha padrão é '$variable'.",
                "from" => $sendernumber
            ]);

            $successMessages[] = "Mensagem enviada para $phoneNumber com sucesso!";
        }

        // Return a success JSON response with all the success messages
        return new JsonResponse(['messages' => $successMessages]);
    } catch (TwilioException $e) {
        $message = 'Falha no envio da mensagem. Por favor, tente novamente mais tarde.';
        \Log::error('Twilio Exception: ' . $e->getMessage());
        return new JsonResponse(['message' => $message], 500);
    }
}



public static function EstudanteComDividasPropinas($studentIDs, $anolectivoID) {
 




$multa = DB::table('multa')->select('percetagem', 'diaCombraca')->first();
// $current = Carbon::today();
// $DAY = $multa->diaCombraca;
// $MONTH = $current->month;
// $YEAR = $current->year;
// Use Carbon to format the date with leading zeros for the day part
// $DaTaCombraca = Carbon::create($YEAR, $MONTH, $DAY)->format('Y-m-d');

$DaTaCombraca = [3];


  $allowedMesesIDs = DB::table('meses')
    ->where('mesAnolectivoID', '=', $anolectivoID)
    ->whereIn('mesID',$DaTaCombraca)
    ->pluck('mesID')
    ->toArray();

 $allowedMesesIDs;
    $studentsWithMissingTransactions = [];
    $studentData = [];


 // $estudantantecontador = count($studentIDs);
    foreach ($studentIDs as $student) {
        $existingMesesIDs = DB::table('transactions')
            ->join('meses', 'meses.mesID', '=', 'transactions.MesesID')
            ->where('studentID', $student->id)
            ->where('Cancelar', 0)
             ->whereIn('mesID',$DaTaCombraca)
            ->pluck('MesesID')
            ->toArray();

         $missingMesesIDs = array_diff($allowedMesesIDs, $existingMesesIDs);
//return $missingMesesIDs;
        $user = DB::table('users')
            ->Leftjoin('pessoa', 'pessoa.id', '=', 'users.pessoa_id')
            //->Leftjoin('users as enacarregado', 'pessoa.id', '=', 'users.pessoa_id')

            ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=','estudante_x_ano_x_classe.Anolectivo_id')
            ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('curso', 'curso.id', '=','estudante_x_ano_x_classe.Curso_id')
            ->where('users.id','=', $student->id)
            ->where(['estudante_x_ano_x_classe.Anolectivo_id' => $anolectivoID])
            ->select( DB::raw("CONCAT(users.ultimo_nome, ' ', users.primeiro_nome) as nomeCompleto"),'email','mensalidade.Classe_id','classe_name','mensalidade.id as studedetalhes','users.nomePai','users.nomeMae','users.numeroDotelefone','telefoneAlternativo','nomeCurso','curso.id as cursoid','ano_lectivo')
            ->first();


         foreach ($missingMesesIDs as $missingMesID) {
            $mesesInfo = DB::table('meses')->where('mesID', $missingMesID)->select('mesID', 'mesNome')->first();

            if (!isset($studentData[$student->id])) {

                $studentData[$student->id] = [
                    'studentID' => $student->id,
                    'studedetalhes'=>$student->studedetalhes,
                    'Classe_id' => $student->Classe_id,
                    'nomeCurso' => $student->nomeCurso,
                    'classe_name' => $student->classe_name,
                    'ano_lectivo' => $student->ano_lectivo,
                   // 'PagamentoMensal' => Pagamento::PagamentoMensal($anolectivoID, $student->Classe_id),
                    'email' => $student->email,
                    'nomeCompleto' => $student->nomeCompleto,
                    'nomePai' => $student->nomePai,
                    'telefoneAlternativo' => $student->telefoneAlternativo,
                    'mesData' => []
                ];
            }

            $studentData[$student->id]['mesData'][] = [
                'mesID' => $mesesInfo->mesID,
                'mesNome' => $mesesInfo->mesNome
            ];
        }
    }


$contarestudante =  count($studentData);
$pagamentoTotal = [];
    foreach ($studentData as $student) {

        $mergedMesData = [];
        foreach ($student['mesData'] as $mes) {
            $mergedMesData[] = [
                'mesID' => $mes['mesID'],
                'mesNome' => $mes['mesNome']
            ];
        }
 
        $PagamentoMensal = Pagamento::PagamentoMensal($anolectivoID, $student['Classe_id'],$student['studedetalhes']);
       
        $meseCount = count($mergedMesData);
       // $ValorDaMulta = MinhasFuncoes::calcularjuros($meseCount * $PagamentoMensal);
         $TotalApagar= $PagamentoMensal * $meseCount;
       // $TotalMulta = $ValorDaMulta * $meseCount;
       //$pagamentoTotal = $TotalMulta + $TotalApagar
       
         $pagamentoTotal = $contarestudante * $TotalApagar;

        $studentsWithMissingTransactions[] = [
        //'percetagem' => $multa->percetagem,  

        'TotalApagar' =>$TotalApagar,
        'PagamentoMensal'=>$PagamentoMensal,
        //'TotalMulta'=>$TotalMulta,
        'NumeroDeMeses' => $meseCount,
        'studentID' => $student['studentID'],
        'nomeCurso' => $student['nomeCurso'],
        'classe_name' => $student['classe_name'],
        'ano_lectivo' => $student['ano_lectivo'],
        'email' => $student['email'],
        'nomeCompleto' => $student['nomeCompleto'],
        'nomePai' => $student['nomePai'],
        'telefoneAlternativo'=>$student['telefoneAlternativo'],
        'mesData' => $mergedMesData
        ];
    }

    return ['estudantescomdividas'=>$studentsWithMissingTransactions,'pagamentoTotal'=>$pagamentoTotal];
}










   public static function NotificationbyWatsapp()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        // Generate PDF using laravel-dompdf or any other library
        $pdf = PDF::loadView('emails.pdf_email', $data);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('app/public/pdfs/') . 'filename.pdf';
        $pdf->save($pdfPath);

        // Get Twilio credentials from config
        $twilioSid = config("services.twilio.sid");
        $twilioToken = config("services.twilio.token");

        // Initialize Twilio client
        $twilio = new Client($twilioSid, $twilioToken);

        // WhatsApp number to send to
           $message = $twilio->messages
            ->create(
                "whatsapp:+244926551976", // to
                [
                    "from" => "whatsapp:+14155238886",
                    "body" => "Your Yummy Cupcakes Company order of 1 dozen frosted cupcakes has shipped and should be delivered on July 10, 2019. Details: http://www.yummycupcakes.com/",
                   // 'mediaUrl' => asset('storage/pdfs/filename.pdf') // Adjust the path if needed
                ]
            );


        return response()->json(['sucess' => $message->sid], 200);
    } 
















 }