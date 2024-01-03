<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faltas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;
use DB;


class EstudanteFaltaController extends Controller
{
    //








/**
 * lista de Faltas de um estudante durante o ano lectivo em uma certa disciplina 
 *
 * @OA\Get (
 *     path="/api/Encarregado/encarregadoconsultarfaltas/anolectivo/{anolectivo}/estudante/{id}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
  *     @OA\Parameter(
 *         name="estudanteid",
 *         in="path",
 *         required=true,
 *         description="ID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="lista de faltas por disciplinas durante o ano lectivo",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="disciplina_id", type="integer",example=15),
*                 @OA\Property(property="attendance_Nome", type="string",example="Ausente"),
*                 @OA\Property(property="nomeDisciplina",type="string",example="EDUCAÇÃO FISICA"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
*                 @OA\Property(property="ausente_count", type="integer",example="3"),
*                 @OA\Property(property="presente_count", type="integer",example="1"),
*                 @OA\Property(property="atrasado_count", type="integer",example="0"),
 *             )
 *         )
 *     )
 * )
 */




public function consultarfaltas($ano) {
    $userId = Auth::id();

    $estudantefaltas = DB::table('attendance')
        ->join('users', 'users.id', '=', 'attendance.studentID')
        ->join('classes', 'classes.id', '=', 'attendance.classeID')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'attendance.anolectivoID')
        ->join('attendance_types', 'attendance_types.id', '=', 'attendance.idattendance_types')
        ->join('disciplinas', 'disciplinas.id', '=', 'attendance.disciplinaID')
        ->select(
            'disciplinas.id as disciplina_id',
            'attendance_Nome',
            'nomeDisciplina',
            'classe_name',
            'ano_lectivo',
            DB::raw("SUM(CASE WHEN attendance_types.id = 1 THEN 1 ELSE 0 END) AS presente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 2 THEN 1 ELSE 0 END) AS ausente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 3 THEN 1 ELSE 0 END) AS atrasado_count")
        )
        ->where(['users.id' => $userId, 'anolectivoID' => $ano])
        ->groupBy('disciplinas.id', 'attendance_Nome', 'nomeDisciplina', 'classe_name', 'ano_lectivo')
        ->get();

    return response()->json([
        'estudantefaltas' => $estudantefaltas,
    ], 200);
}
















}
