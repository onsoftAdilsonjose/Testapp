<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Ajuda;

class AnoLectivoEstudanteLogadoController extends Controller
{
    //








/**
 *filtro de Ano Lectivo Para Estudante Logado.
 *
 * @OA\Get (
 *     path="/api/Estudante/estudantefilter",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de pagamento do estudante para o ano letivo especificado",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *                 @OA\Property(property="anolectivoid", type="integer",example=1),
 *                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
 *                 @OA\Property(property="classeid",type="integer",example=1),
 *             )
 *         )
 *     )
 * )
 */





    public function EstudanteFilter(){

         $Anolectivoestudantelogado  = Ajuda::FilterAnolectivoEstudanteloggado();

      
          return response()->json(['Anolectivoestudantelogado'=>$Anolectivoestudantelogado]);


    }










}
