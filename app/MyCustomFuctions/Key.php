<?php

namespace App\MyCustomFuctions;

use App\Models\Center\Key\Provider\Service\Keygerate;
use App\MyCustomFuctions\Key;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
class Key
{
   


 

public static function keys() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < 12; $i++) {
        $string .= strtoupper($characters[mt_rand(0, strlen($characters) - 1)]);
    }

    // Check if the generated string is unique (not used before)
    // You should implement a check against your database or storage

    return $string;
}




public static function validar($license) {
//$license= Crypt::decrypt($decrypted);
//$license = trim($license);	
    $currentDate = now();
    $currentMonthName = $currentDate->format('Y-m-d');
    $nextMonthName = $currentDate->addMonth()->format('Y-m-d');


$validLicense = Keygerate::where('activated', 0)
    ->where('key', $license)
    ->whereNull('startday')
    ->whereNull('endday')
    ->exists();


$licenseExpired = Keygerate::where('activated', 1)
    ->where('key', $license)
    ->whereNotNull('startday')
    ->where('endday', '<', $currentMonthName)
    ->exists();

$licenseEmuso = Keygerate::where('activated', 1)
    ->where('key', $license)
    ->whereNotNull('startday')
    ->where('endday', '>', $currentMonthName)
    ->exists();


$chave = DB::table('serialicense')->where('key', $license)->first();


if ($validLicense && $chave) {
    $currentDate = now();
    $currentMonthName = $currentDate->format('Y-m-d');
    $nextMonthName = $currentDate->addMonth()->format('Y-m-d');



    // Prepare data for HTTP POST request
    $endpoint = 'https://controllincesesystem-production.up.railway.app/api/updateData';
    // $endpoint = 'onschool.up.railway.app/api/updateData';
    $data = [
        'status' => 1,
        'start_date' =>$currentMonthName,
        'expiration_date' =>$nextMonthName,
        'license_key' => $license,
    ];

    try {
        // Make the HTTP POST request
        $response = Http::post($endpoint, $data);


            if ($response) {
            DB::table('serialicense')->where('key', $license)->update([
            'activated' => 1,
            'Meses' => 1,
            'startday' => $currentMonthName,
            'endday' => $nextMonthName,
            // Add other columns and data as needed
            ]);


            return response()->json(['message' => 'A licença foi validada com sucesso.'], 200);
        } else {
            return response()->json(['message' => 'Erro ao enviar dados para o servidor externo.'], 500);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Erro ao conectar-se ao servidor externo: ' . $e->getMessage()], 500);
    }
} else {


// if ($licenseExpired) {
// $result = 'License has expired';
// } elseif ($licenseEmuso) {
// return response()->json(['message' => 'A licença ainda e valida.'], 404);
// } else {
// return response()->json(['message' => 'A licença não é válida ou está indisponível.'], 404);
// }

$result = $licenseExpired ? 'License has expired' : ($licenseEmuso ? 'A licença ainda é válida.' : 'A licença não é válida ou está indisponível.');

 return response()->json(['message' => $result], 404);
   
}


 

}






}


 