<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ProcessoDisciplinar;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Docs;

class EstudanteCondutaController extends Controller
{
    //






/**
 * lista do Comportamento do estudante 
 *
 * @OA\Get (
 *     path="/api/Estudante/condutaestudante/anolectivo/{anolectivo}",
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
 *         description="Lista de comportamento do estudante",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="id", type="integer",example=10),
*                 @OA\Property(property="testemunha", type="string",example="adilson jose Miguel"),
*                 @OA\Property(property="motivo",type="string",example="Luta na escola"),
*                 @OA\Property(property="data", type="string",example="2023-09-03"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="registradopor", type="string",example="Adimin Admin"),
 *             )
 *         )
 *     )
 * )
 */





public function EstudanteCondutaanual($ano){
$userId = Auth::id();
$estudanteConduta =DB::table('processodisciplinar')
->join('users', 'users.id', '=', 'processodisciplinar.registradopor')
->join('classes', 'classes.id', '=', 'processodisciplinar.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'processodisciplinar.Anolectivo_id')
->select('processodisciplinar.id as id','testemunha','motivo','data','ano_lectivo','classe_name',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS registradopor"))
->where(['student_id'=>$userId,'Anolectivo_id'=>$ano])
->get();



//student_id

return response()->json(['estudanteConduta' => $estudanteConduta], 200);




}
}
