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

class MinhasFuncoes
{
    public static function SmsMarketing($variable)
    {




        try {
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $sendernumber = getenv("TWILIO_PHONE_NUMBER");
            $twilio = new Client($sid, $token);

            $message = $twilio->messages->create("+244926551976", // to
                [
                    "body" => "Obrigado por ser Matriculado no Colegio Y! Sua conta foi criada com sucesso. Sua senha padrão é '$variable'.",
                    "from" => $sendernumber
                ]
            );
            $message = 'Mensagem enviada com sucesso!';
            // Success! The message was sent.
            // Return a success JSON response.
            return new JsonResponse(['message' => $message]);
        } catch (TwilioException $e) {
            // An exception occurred during the Twilio API call.
            // Handle the error here or log it for further investigation.
            $message = 'Falha no envio da mensagem. Por favor, tente novamente mais tarde.';
            // For example, you can log the error message:
            \Log::error('Twilio Exception: ' . $e->getMessage());

            // Return an error JSON response.
            return new JsonResponse(['message' => $message], 500);
        }
    }

    public static function RegNumero($name)
    {
        $firstTwoLetters = substr($name, 0, 2);
        ///primeirasLetrasMaiusculas
        $LM = strtoupper($firstTwoLetters);
        $reg_Numero = IdGenerator::generate(['table' => 'users', 'field' => 'reg_Numero', 'length' => 10, 'prefix' => '' . $LM . '' . '' . date('yndsi')]);
        return $reg_Numero;
    }

    public static function Multa($months)
    {
        $anolectivoID = 1;
        $studentID = 3;
        $classID = 7;
        $monthsWithoutPayment = [];

        foreach ($months as $month) {
            $currentMonth = date('Y-n');
            // Check if the current date is less than the month being paid
            if ($currentMonth > $month) {
                $payment = Transactions::where('MesesID', $month)
                    ->where('classID', $classID)
                    ->where('studentID', $studentID)
                    ->where('anolectivoID', $anolectivoID)
                    ->where('Cancelar', 0)
                    ->first();

                if (!$payment) {
                    // Add the month to the list of months without payment
                    $monthsWithoutPayment[] = $month;
                }
            }
        }

        return $monthsWithoutPayment;
    }

    public static function calculateDiscount($price, $discountPercentage)
    {
        $discountAmount = ($price * $discountPercentage) / 100;

        // Calculate the discounted product price
        $discountedPrice = $price - $discountAmount;
        $amountDescounted = $price - $discountedPrice;
        return $amountDescounted;
    }

    public static function generatePDF($data)
    {
        $pdf = PDF::loadView('emails.pdf_email', $data);
        try {
            Mail::to('adilson2012jose@gmail.com')->send(new SendPDFEmail($pdf));
            return response()->json(['message' => 'E-mail com anexo em PDF enviado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao enviar e-mail'], 500);
        }
    }

     public static function calcularjuros($amount)
    {

        $multa = DB::table('multa')->select('percetagem','diaCombraca')->first();
        $interestRate = $multa->percetagem; // 10% interest rate
        $interestCharge = ($amount * $interestRate) / 100;
        return $interestCharge;
    }

 
public static function checkMonths($months, $anolectivoID, $studentID, $classID,$SingleStudentDetalhes)
{

$mensalidade = DB::table('mensalidade')
    ->where(['Classe_id' => $classID, 'Anolectivo_id' => $anolectivoID,'id'=>$SingleStudentDetalhes])
    ->select('id', 'Propina_Anual')
    ->first();

 $countMeses = Pagamento:: CountMeses($anolectivoID,$classID,$SingleStudentDetalhes);
 $MultaporMes = MinhasFuncoes::calcularjuros($mensalidade->Propina_Anual/$countMeses);

 




$multa = DB::table('multa')->select('percetagem','diaCombraca')->first();
$current = Carbon::today();
$DAY = $multa->diaCombraca;
$MONTH = $current->month;
$YEAR = $current->year;
$DaTaCombraca = $YEAR.'-'.$MONTH.'-'.$DAY;

//2023-08-12

  
    $totalCount = 0;
    $filteredData = [];

    foreach ($months as $month) {
        // Check if the record exists in the Transactions model based on the given conditions
        $recordExists = Transactions::where('MesesID', $month)
            ->where('classID', $classID)
            ->where('studentID', $studentID)
            ->where('anolectivoID', $anolectivoID)
            ->where('transactions.Cancelar', '=', 0)
            ->exists();

        if (!$recordExists) {
            // Fetch the data for non-existing months
            $filteredDataItem = Meses::select('mesNome', 'mesID')
                ->where('mesID', '=', $month)
                ->whereDate('Data', '<', $DaTaCombraca) // Compare the dates
                ->first();

            if ($filteredDataItem) {
                $totalCount++; // Increment the count
                $filteredData[] = [
                    'mesID' => $filteredDataItem->mesID,
                    'mesNome' => $filteredDataItem->mesNome,
                    'MultaporMes' =>  round($MultaporMes,2),
                ];
            }
        }
    }

    // Return the result array with count and filtered data
    return ['totalCount' => $totalCount, 'MesesComMultas' => $filteredData];
}


}


 