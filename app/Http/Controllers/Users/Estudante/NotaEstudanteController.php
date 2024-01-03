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
use App\Estudante\EstudanteInfounico;

class NotaEstudanteController extends Controller
{
    //







/**
 * Lista de Todas Disciplinas Com Suas Respectivas Notas e Media Trimestral
 *
 * @OA\Get (
 *     path="/api/Estudante/vernotas/anolectivo/{ano}",
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
 *         description="Lista da notas do ano lectivo escolhido",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="Mac1", type="number",example="17"),
*                 @OA\Property(property="Npt1", type="number",example="12"),
*                 @OA\Property(property="Npp1", type="number",example="11"),
*                 @OA\Property(property="Mac2", type="number",example="17"),
*                 @OA\Property(property="Npt2", type="number",example="12"),
*                 @OA\Property(property="Npp2", type="number",example="11"),
*                 @OA\Property(property="Mac3", type="number",example="17"),
*                 @OA\Property(property="Npt3", type="number",example="12"),
*                 @OA\Property(property="Npp3", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *                 @OA\Property(property="MediaSegundoTrimestre", type="string",example="12"),
 *                 @OA\Property(property="MediaTerceriroTrimestre", type="integer",example="18"),

 *             )
 *         )
 *     )
 * )
 */



    public function Estudantenotas($anolectivoID){

       $tudentid = Auth::id();
         
       $classeId = EstudanteInfounico::getstudentInfo($anolectivoID,$tudentid);

       $notas = Ajuda::Todasnotas($anolectivoID,$classeId->Classe_id);

          return response()->json([
            $notas

        ]);
    }







/**
 * lista de Historico de Todas Notas e Disciplinas 
 *
 * @OA\Get (
 *     path="/api/Estudante/vernotas/historico/",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista da notas do ano lectivo escolhido",
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
*                 @OA\Property(property="Mac2", type="number",example="17"),
*                 @OA\Property(property="Npt2", type="number",example="12"),
*                 @OA\Property(property="Npp2", type="number",example="11"),
*                 @OA\Property(property="Mac3", type="number",example="17"),
*                 @OA\Property(property="Npt3", type="number",example="12"),
*                 @OA\Property(property="Npp3", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *                 @OA\Property(property="MediaSegundoTrimestre", type="string",example="12"),
 *                 @OA\Property(property="MediaTerceriroTrimestre", type="string",example="18"),

 *             )
 *         )
 *     )
 * )
 */
                


    public function Historicos(){
         

        $historico = Ajuda::historico();

          return response()->json([
            $historico

        ]);
    }











    
}
