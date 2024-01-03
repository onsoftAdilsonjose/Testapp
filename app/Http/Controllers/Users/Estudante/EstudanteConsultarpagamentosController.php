<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
 use Illuminate\Support\Facades\Auth;

class EstudanteConsultarpagamentosController extends Controller
{
    //
 
/**
 * Detalhes de De Todos Os Pagamentos que foram Feito Anualmente
 * @OA\Get (
 *     path="/api/Estudante/ConsultarPagamento/anolectivo/{anolectivoid}",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalhes de Consulta de Todos os Pagamnetos Feitos",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="Professor Professor", type="string",example="Joao Miguel"),
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *             )
 *         )
 *     )
 * )
 */


public function ConsultarPagamento($anolectivo){

$userId = Auth::id();
$relatorioDepropina = DB::table('transactions')
->join('meses', 'meses.id', '=', 'transactions.MesesID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'transactions.anolectivoID')
->join('payments', 'payments.id', '=', 'transactions.payment_id')
->select(
'transactions.paymentOrder',
'ano_lectivo',
'mesNome',
'transactions.Multa',
'transactions.Preco',
'transactions.Descount',
'transactions.anolectivoID',
'transactions.studentID',
//'payments.id as paymentid'
)
->where(['transactions.studentID'=>$userId,'payments.Cancelar'=>0])
->orderBy('orderNumber', 'ASC') // Add this line to order by orderNumber
->get();

$collection = collect($relatorioDepropina);
$propinas = $collection->groupBy('paymentOrder');
$propinas->all();


$relatorioDeservico = DB::table('transatiosservico')
->join('servicos', 'servicos.id', '=', 'transatiosservico.servicoID')
->join('payments', 'payments.id', '=', 'transatiosservico.payment_id')

->select(
'payments.paymentOrder',
'transatiosservico.Quantidade',
'transatiosservico.Preco',
'servicos.ServicoNome',
'transatiosservico.Descount',
'transatiosservico.payment_id',
'servicoID',
//DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((transatiosservico.Preco * transatiosservico.Quantidade - Descount), 2) END as servicoTotal')
)
->where(['transatiosservico.studentID'=>$userId])
->get();

$collection1 = collect($relatorioDeservico);
$servicos = $collection1->groupBy('paymentOrder');
$servicos->all();

return response()->json([
'propinas'=>$propinas,
'servicos'=>$servicos
]);

}

  

 /**
 * Extrato Finaceiro de Todos os pagamentos Feito no Sistema
 * @OA\Post (
 *     path="/api/Estudante/ExtratoFinaceiro",
 *     tags={"Estudante"},
 *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="starting_Date", type="string"),
 *                 @OA\Property(property="ending_Date", type="string"),
 *                 @OA\Property(property="anolectivo", type="integer"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="password actualizado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="starting_Date", type="string", example="2023-11-18"),
 *             @OA\Property(property="ending_Date", type="string", example="2023-11-18"),
 *             @OA\Property(property="anolectivo", type="integer", example=1),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid data",
 *         @OA\JsonContent(
 *             @OA\Property(property="msg", type="string", example="Falha na validação"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Creation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Falha na actualização"),
 *         )
 *     )
 * )
 */


   public function ExtratoFinaceiro(Request $request){
		$starting_Date ='2023-11-18';
		$ending_Date ='2023-11-18';
		$anolectivo = 1;











   

    }

}
