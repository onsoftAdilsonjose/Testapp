<?php

namespace App\Http\Controllers\Users\Encarregado;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Estudante_x_Ano_x_Classe;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Encarregado\EncarregadoFunctions;

class FinacaController extends Controller
{
    //


/**
 * Detalhes de De Todos Os Multas e Prazos que foram Feito Anualmente
 *
 * @OA\Get (
 *     path="/api/Encarregado/MultasePrazos/anolectivo/{anolectivoid}/estudante/{estudanteid}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="anolectivo",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalhes de Multas e Prazo de Todos os Pagamentos Feitos",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="PagamentoMensal", type="integer", example=5900),
 *             @OA\Property(property="ValorDaMulta", type="integer", example=1770),
 *             @OA\Property(property="MesesComMultas", type="integer", example=3),
 *             @OA\Property(property="MesesPago", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="mesNome", type="string", example="June"),
 *                     @OA\Property(property="mesID", type="integer", example=6),
 *                     @OA\Property(property="mesAnolectivoID", type="integer", example=1)
 *                 )
 *             ),
 *             @OA\Property(property="TotalMulta", type="integer", example=5310),
 *             @OA\Property(property="Divida", type="integer", example=41300)
 *         )
 *     )
 * )
 */





 public function MultasePrazosEncarregado($ano,$userId){



$classe = EncarregadoFunctions::getstudentInfoEncarregado($ano,$userId);

$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($classe->Classe_id,$classe->Anolectivo_id,$userId);
 $months = Pagamento::Months($classe->Classe_id,$SingleStudentDetalhes);
$MesComDivida = Pagamento::MesesComDivida($userId, $classe->Anolectivo_id,$classe->Classe_id,$SingleStudentDetalhes); 
$MesesPago = Pagamento::MesesPago($userId, $classe->Anolectivo_id,$classe->Classe_id);
$PagarApartir = Pagamento::PagarApartir($userId,$classe->Anolectivo_id,$classe->Classe_id);


$PagamentoMensal = Pagamento::PagamentoMensal($classe->Anolectivo_id,$classe->Classe_id,$SingleStudentDetalhes);
$CountMesComDivida = count($MesComDivida);
$Divida =$CountMesComDivida * $PagamentoMensal;


$resultArray = MinhasFuncoes::checkMonths($months,$classe->Anolectivo_id, $userId,$classe->Classe_id,$SingleStudentDetalhes);
$MesesComMultas = round($resultArray['totalCount'],2);
$mesesIDComMulta = $resultArray['MesesComMultas'];
$ValorDaMulta = MinhasFuncoes::calcularjuros($MesesComMultas * $PagamentoMensal);


return [
'PagamentoMensal' => round($PagamentoMensal,2),	
'ValorDaMulta'=> round($ValorDaMulta,2),
'MesesComMultas'=> $MesesComMultas,
'MesesPago' => $MesesPago,
'MesComDivida' => $MesComDivida,
'PagarApartir' => $PagarApartir,
'mesesIDComMulta' => $mesesIDComMulta,
'MesesComMultas'=> $MesesComMultas,
'TotalMulta' =>round($ValorDaMulta*$MesesComMultas,2),
'Divida'=>$Divida,

];

}







/**
 * Relatorio de De  Pagamentos que foram Feito Anualmente
 *
 * @OA\Get (
 *     path="/api/Encarregado/relatoriodePagamento/anolectivo/{anolectivoID}/studentID/{studentID}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="anolectivo",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalhes de Relatorio de Todos os Pagamentos Feitos",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="PagamentoMensal", type="integer", example=5900),
 *             @OA\Property(property="ValorDaMulta", type="integer", example=1770),
 *             @OA\Property(property="MesesComMultas", type="integer", example=3),
 *             @OA\Property(property="MesesPago", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="mesNome", type="string", example="June"),
 *                     @OA\Property(property="mesID", type="integer", example=6),
 *                     @OA\Property(property="mesAnolectivoID", type="integer", example=1)
 *                 )
 *             ),
 *             @OA\Property(property="TotalMulta", type="integer", example=5310),
 *             @OA\Property(property="Divida", type="integer", example=41300),
 *             @OA\Property(property="relatoriodePagamento", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="ValorPago", type="string", example="20970.00"),
 *                     @OA\Property(property="Tipodepagamento", type="string", example="Dinheiro"),
 *                     @OA\Property(property="paymentOrder", type="string", example="PROP-2023120639421"),
 *                     @OA\Property(property="Descount", type="string", example="0"),
 *                     @OA\Property(property="Status", type="integer", example=0),
 *                     @OA\Property(property="SaldoGuardado", type="string", example="0"),
 *                     @OA\Property(property="SaldoRemovido", type="string", example="0")
 *                 )
 *             )
 *         )
 *     )
 * )
 */


  public function relatoriodePagamentoEncarregado($anolectivoID,$studentID){


$userId = Auth::id();

$relatoriodePagamento = DB::table('payments') 
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'payments.anolectivoID')
->join('users', 'users.id', '=', 'payments.studentID')
->select(
'ValorPago',
'Tipodepagamento',
'paymentOrder',
'Descount',
'Cancelar as Status',
'SaldoGuardado',
'SaldoRemovido',

)
->where([
	'payments.studentID'=>$studentID,
	 'payments.anolectivoID'=>$anolectivoID,
	 'estudante_x_ano_x_classe.Anolectivo_id'=>$anolectivoID,
	'users.encarregadoID'=>$userId,
	'payments.Cancelar'=>0,
])
->get();

return response()->json([
'relatoriodePagamento' => $relatoriodePagamento

]);


}










}
