<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use NumberToWords\NumberToWords;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Notification;
use App\MyCustomFuctions\Months;
use App\Models\Payment;
use App\Models\User;
use DB;
use App\Models\Transactions;
use App\MyCustomFuctions\Pagamento;
use App\MyCustomFuctions\AprovadoOrReprovado;
use App\MyCustomFuctions\MatricularEstudante;
use App\MyCustomFuctions\Key;
use Carbon\Carbon;
use App\Services\TwilioService;
use Storage;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use PDF;
use Illuminate\Support\Facades\Http;
use App\Mail\SendEmailMatricula;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Crypt;
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
 
 
      



public function Testeste(Request $request){






$student_id = 208;
$anolectivo_id = 1;
$classe_id = 11 ;





 $MatricularEstudante = MatricularEstudante::MatricululaorConfirmacao($student_id,$anolectivo_id,$classe_id);



















 return response()->json([$MatricularEstudante]);

}




 














// public function Testeste(Request $request){



// $validar = Key::validar($request->chave);

// //$dencryptedData = Crypt::decrypt($chave);
//  return response()->json($validar);

// }















































 



    /**
     * Display the specified resource.
     */
public function processData()
{

$studentIDs = DB::table('users')
    ->join('role_user', 'users.id', '=', 'role_user.user_id') // Pivot table
    ->join('roles', 'role_user.role_id', '=', 'roles.id')
    //->where('users.id', '=', $student->id) // Use '=' for equality comparison
    ->where('roles.id', '=', 4) // Filter by role ID 4
    ->select('users.id','telefoneAlternativo','primeiro_nome','ultimo_nome')
    ->get();


    $anolectivoID = DB::table('ano_lectivos')->select('id')->first();
    $studentData =  Notification::Devedores($studentIDs, $anolectivoID->id);

    $lastStudentID = null;
    $lastPrimeiroNome = null;
    $lastUltimoNome = null;
    $lastTelefoneAlternativo = null;
    $lastMesNome = [];

    foreach ($studentData as $student) {
        $lastStudentID = $student['studentID'];
        $lastPrimeiroNome = $student['primeiro_nome'];
        $lastUltimoNome = $student['ultimo_nome'];
        $lastTelefoneAlternativo = $student['telefoneAlternativo'];

        foreach ($student['mesData'] as $mes) {
            $lastMesNome[] = $mes['mesNome'];
        }
    }
         return  [$lastPrimeiroNome,$lastMesNome];



    // Now $accumulatedMesIDs contains all the mesIDs from the loop

    // You can use $accumulatedMesIDs here or return it as needed
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
