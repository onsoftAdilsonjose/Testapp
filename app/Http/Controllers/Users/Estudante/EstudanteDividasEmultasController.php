<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Estudante_x_Ano_x_Classe;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Estudante\EstudanteInfounico;

class EstudanteDividasEmultasController extends Controller
{



/**
 * Todos os Detalhes de Pagamento Estaram neste Request
 *
 * @OA\Get (
 *     path="/api/Estudante/MultasePrazos/anolectivo/{anolectivoid}",
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
 *         description="Detalhes Concernentes As Multas Dividas ,Meses Pago ,Meses Com Divida ,Meses Com Multa Tudo esta aqui neste Request",
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
 *             ),
  *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="Estudante", type="string",example="Joao Miguel"),
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



public function MultasePrazos($ano){


$userId = Auth::id();
$classe = EstudanteInfounico::getstudentInfo($ano,$userId);

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














    
}
