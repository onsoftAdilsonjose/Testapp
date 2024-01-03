<?php

namespace App\MyCustomFuctions;

use NumberToWords\NumberToWords;
use DB;
use App\Models\Transactions;
use App\Models\Meses;
use App\Models\classes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Role;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Http\JsonResponse;
use PDF;
use App\Mail\SendPDFEmail;
use Illuminate\Support\Facades\Mail;


class Months
{
     


public static function Trimestre() {
    $anolectivoID = 1;
    $ano_lectivos = DB::table('ano_lectivos')->where('id', '=', $anolectivoID)->first();

    $inputDate = date($ano_lectivos->inicio);

    // Convert the input date to a Carbon instance
    $startDate = Carbon::createFromFormat('Y-m-d', $inputDate);
    $PrimeiroTrimestre= Carbon::parse($startDate)->format('Y-m');
    // Initialize an empty arrays to store the months
    $months1 = [];
    $months2 = [];
    $months3 = [];

    // Initialize a variable to store the last month
    $SegundoTrimestre = null;
    $TerceiroTrimestre = null;
    $lastMonth3 = null;

 $currentDate = Carbon::now();
 $formattedDate = $currentDate->format('Y-m');



    // Loop through the next three months for the first set
    for ($i = 0; $i < 4; $i++) {
        $currentMonth = $startDate->copy()->addMonths($i)->format('Y-m');
        $months1[] = $currentMonth;

        // Update the last month for the first set
        $SegundoTrimestre = $currentMonth;
    }

    // Calculate the start date for the second set using the last month from the first set
    $startDate2 = Carbon::createFromFormat('Y-m', $SegundoTrimestre);

    // Loop through the next three months for the second set
    for ($i = 0; $i < 4; $i++) {
        $currentMonth = $startDate2->copy()->addMonths($i)->format('Y-m');
        $months2[] = $currentMonth;

        // Update the last month for the second set
        $TerceiroTrimestre = $currentMonth;
    }

    // Calculate the start date for the third set using the last month from the second set
    $startDate3 = Carbon::createFromFormat('Y-m', $TerceiroTrimestre);

    // Loop through the next three months for the third set
    for ($i = 0; $i < 4; $i++) {
        $currentMonth = $startDate3->copy()->addMonths($i)->format('Y-m');
        $months3[] = $currentMonth;

        // Update the last month for the third set
        $lastMonth3 = $currentMonth;
    }






$I = ($PrimeiroTrimestre > $formattedDate ) ? 1 : 0 ;
$II = ($SegundoTrimestre > $formattedDate ) ? 1 : 0 ;
$III = ($TerceiroTrimestre> $formattedDate ) ? 1 : 0 ;









    return response()->json([
'PrimeiroTrimestre' => $I,
'SegundoTrimestre' => $II,
'TerceiroTrimestre' => $III,



//'nextThreeMonths2' => $months2,
//'nextThreeMonths3' => $months3,
//'lastMonth3' => $lastMonth3,
    ]);
}




public static function MesesFunc($startDate){


$carbonDate = Carbon::parse($startDate);
$threeLetterMonth = $carbonDate->format('M');
$monthNumber = $carbonDate->format('n');



return [$threeLetterMonth,$monthNumber];


}




 














}


 