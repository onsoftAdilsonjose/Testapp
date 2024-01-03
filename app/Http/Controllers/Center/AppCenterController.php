<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Center\Key\Provider\Service\Keygerate;
use App\Models\InformacaoDaEscola;
use App\MyCustomFuctions\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AppCenterController extends Controller
{
    //


public function GuardaLincese(Request $request){

$firstDate = DB::table('serialicense')->get();
$currentDate = Carbon::now();
$actualDate = Carbon::now();
$futureDate = $currentDate->addMonths(2);
$countFirstDate = $firstDate->count();

if ($countFirstDate == 0) {
$salvarlinceca= new Keygerate;
$salvarlinceca->Meses = 2; 
$salvarlinceca->startday = $actualDate;
$salvarlinceca->endday = $futureDate ;
$salvarlinceca->activated = 1; 
$salvarlinceca->key = 'RkPL4joinY5JF4B'; 
$salvarlinceca->save();
return response()->json(['msg'=>'Primeira Linceca de Test de 2 Meses'],200);
}else{
$Meses= mt_rand(1,12);
$key= Str::random(15);
$salvarlinceca= new Keygerate;
$salvarlinceca->Meses = $Meses; 
//$salvarlinceca->key = Hash::make($request->key); 
$salvarlinceca->key =$key; 
$salvarlinceca->save();
return response()->json([
'msg'=>'linceca Criada com Successo']);

}


      


}

 



 public function ValidateLincese(Request $request)
{


    //// na execucao de este codigo  a tabela seriallince deve coonter dados que serao inserido manual
    //manual  1-primeira lincense de teste.

    //    $chave = Keygerate::where('key', $request->chave)->first();
    //    $actualDate = Carbon::now();
    //    $currentDate = Carbon::now();
       
    //     if (!$chave) {
    //         return response()->json(['error' => 'Chave não encontrada'], 404);
    //     }

    //     if ($chave) {
    //      $expiredData = DB::table('serialicense')->where('endday', '<', $actualDate)->get();
    //      $countExpiredData = $expiredData->count();


    // if ($countExpiredData > 0 ) {
    // $futureDate = $currentDate->addMonths($chave->Meses);
    // $deletExpiredData = DB::table('serialicense')->where('endday', '<', $actualDate)->delete();
    
    // $Saved = Keygerate::updateOrCreate(['key' => $request->key],['activated' =>1,'startday'=>$actualDate,'endday'=>$futureDate]);

    //  return response()->json(['Successo' => 'Nova Linceca Registrada Com Successo'], 200);


    // } else {
    //    return response()->json(['error' => 'Ha Linceca Em Uso Ainda E Valida'], 200);
    // }
    







    //     }





   
        }











public function ValidarLicense(Request $request){



$validar = Key::validar($request->chave);

 return response()->json($validar);

}














}
