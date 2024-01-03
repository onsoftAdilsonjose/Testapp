<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Ajuda;
use App\Estudante\EstudanteInfounico;
 use Illuminate\Support\Facades\Auth;
class EstudanteBoletimController extends Controller
{
    //

 

/**
 * lista de Boletim de um Determinado Trimestre 
 *
 * @OA\Get (
 *     path="/api/Estudante/boletim/anolectivo/{anolectivo}/trimestre/{trimestreid}",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="trimestre",
 *         in="path",
 *         required=true,
 *         description="ID do trimestre",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Boletim de Notas do Trimestre Escolhido",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
*                 @OA\Property(property="Mac1", type="number",example="17"),
*                 @OA\Property(property="Npt1", type="number",example="12"),
*                 @OA\Property(property="Npp1", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *             )
 *         )
 *     )
 * )
 */
public function Boletim($ano,$trimestre){
$userId = Auth::id();
$classe = EstudanteInfounico::getstudentInfo($ano,$userId);
$Boletim= Ajuda::Boletimtrimestras($trimestre,$ano,$classe->Classe_id);

return response()->json(['Boletim'=>$Boletim],200);

}











}
