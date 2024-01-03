<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Ajuda;
use App\Models\DisciplinaParaClasse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstudanteDisciplinaController extends Controller
{
    //







/**
 * lista de Disciplina Determinado Ano Lectivo
 *
 * @OA\Get (
 *     path="/api/Estudante/disciplinas/anolectivo/{ano}",
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
 *         description="Lista de Disciplinas de um determiando Ano Lectivo",
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




 public function EstudaTodasDisciplina($anolectivo){

$userId = Auth::id();
$disciplinaporanolectivo = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where('estudante_x_ano_x_classe.student_id', $userId)
->where('estudante_x_ano_x_classe.Anolectivo_id', $anolectivo)
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo',
DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS professor"))
->get();

return $disciplinaporanolectivo;
}




/**
 * lista de Grade curricular Determinado 
 *
 * @OA\Get (
 *     path="/api/Estudante/disciplinas/gradecurricular",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Grade curricular",
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
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\2024"),
 *             )
 *         )
 *     )
 * )
 */
public function Gradecurricular(){

$userId = Auth::id();

$Gradecurricular = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where('estudante_x_ano_x_classe.student_id', $userId)
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo')->get();
return $Gradecurricular;
}








/**
 * lista de Disciplinas Pendentes or para Recurso
 *
 * @OA\Get (
 *     path="/api/Estudante/disciplinas/pendentes/anolectivo/{ano}",
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
 *         description="lista de Disciplinas Pendentes or para Recurso",
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

public function Disciplinaspendentes($ano){

$userId = Auth::id();

$Disciplinaspendentes = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where('estudante_x_ano_x_classe.student_id', $userId)
->where('estudante_x_ano_x_classe.Anolectivo_id', $ano)
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo')->get();
return $Disciplinaspendentes;
}






}










