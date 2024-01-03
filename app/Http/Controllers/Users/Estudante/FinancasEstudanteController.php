<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Disciplina;
use App\Models\Estudante_x_Ano_x_Classe;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use App\Helpers\Ajuda;
use App\Models\EstudanteSaldo;
use App\Estudante\EstudanteInfounico;


class FinancasEstudanteController extends Controller
{
    //






/**
 * Consultar Saldo
 *
 * @OA\Get (
 *     path="/api/Estudante/consultarsaldo",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Grade curricular",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="saldo", type="number",example="10.000"),
 *             )
 *         )
 *     )
 * )
 */
public function ConsultarSaldo(){
$userId = Auth::id();
$Saldo = Pagamento::Saldo($userId);
return response()->json([
        'saldo'=>$Saldo
    ]);

}





// public function ConsultarPagamento($anolectivo){

// $userId = Auth::id();	
// $Alunoids = EstudanteInfounico::getstudentInfo($anolectivo,$userId);
// //$MesesPago = Pagamento::MesesPago ($Alunoids->student_id, $Alunoids->Anolectivo_id, $Alunoids->Classe_id);



//  $payment= DB::table('payments')
// ->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
// ->select(
// 'ValorPago',
// 'Tipodepagamento',
// 'paymentOrder',
// 'Descount',
// 'Cancelar as Status',
// 'SaldoGuardado',
// 'SaldoRemovido','payments.id as id')
// ->where(['studentID'=>$Alunoids->student_id,'classID'=>$Alunoids->Classe_id,'anolectivoID'=>$Alunoids->Anolectivo_id])
// ->first();
 



// $propinas = DB::table('transactions')
// ->join('meses', 'meses.id', '=', 'transactions.MesesID')
// ->where(['studentID'=>$Alunoids->student_id,'classID'=>$Alunoids->Classe_id,'anolectivoID'=>$Alunoids->Anolectivo_id])
// ->select('Multa','Preco','Descount','mesNome')
// ->orderBy('meses.orderNumber', 'ASC')
// ->get();

// $Servico = DB::table('transatiosservico')
// ->where(['studentID'=>$Alunoids->student_id,'classID'=>$Alunoids->Classe_id,'anolectivoID'=>$Alunoids->Anolectivo_id])
// ->select('transatiosservico.*')->get();

 
// $Matricula = DB::table('registro')
// ->where(['student_id'=>$Alunoids->student_id,'Classe_id'=>$Alunoids->Classe_id,'Anolectivo_id'=>$Alunoids->Anolectivo_id])
// ->select('registro.*')->get();

 




// return response()->json([
// 'propinas'=>[$propinas ,'descricao'=>'propinas'],
// 'Servico'=>[$Servico,'descricao'=>'Servico'],
// 'Matricula'=>[$Matricula,'descricao'=>'Matricula/Confirmacao']
// ]);


// }







}
