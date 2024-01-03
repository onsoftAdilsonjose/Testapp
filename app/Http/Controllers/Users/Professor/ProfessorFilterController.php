<?php

namespace App\Http\Controllers\Users\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class ProfessorFilterController extends Controller
{
    //
    /**
 * lista do ano lectivo do professor
 *
 * @OA\Get (
 *     path="/api/Professor/profanolectivo",
 *     tags={"Professor/Filtro"},
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
 *         description="Lista de Cursos",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="id", type="integer",example=2),
 *             )
 *         )
 *     )
 * )
 */


    public function proffilteranolectivo()
    {
 
		$professorId = Auth::id();
		$anolectivo = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
		->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
		->where('Professor_id','=',$professorId)
		->select(
		'ano_lectivo','Anolectivo_id as id')
		->distinct()
		->get('Anolectivo_id');
        return response()->json(['ano_lectivos'=> $anolectivo]);


    }





/**
 * lista do Classe do professor de um determinado anolectivo 
 *
 * @OA\Get (
 *     path="/api/Professor/proffclasse/anolectivo/{anolectivo}",
 *     tags={"Professor/Filtro"},
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
 *         name="classe",
 *         in="path",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Classes",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="id", type="integer",example=11),
 *             )
 *         )
 *     )
 * )
 */



    public function proffilterclasse($anolectivo)
    {
 
		$professorId = Auth::id();
		$classe = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
        ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
		->where(['Professor_id'=>$professorId,'Anolectivo_id'=>$anolectivo])
		->select(
		'classe_name','Classe_id as id')
		->distinct()
		->get('Classe_id');
        return response()->json(['Classes'=> $classe]);

    }

/**
 * lista do Curso do professor de um determinado anolectivo 
 *
 * @OA\Get (
 *     path="/api/Professor/proffiltercurso/anolectivo/{anolectivo}/classe/{classe}",
 *     tags={"Professor/Filtro"},
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
 *         name="classe",
 *         in="path",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Cursos",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="id", type="integer",example=2),
 *             )
 *         )
 *     )
 * )
 */

    public function proffiltercurso($anolectivo,$classe)
    {
 
		$professorId = Auth::id();
		$curso = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
         ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
		->where(['Professor_id'=>$professorId,'Anolectivo_id'=>$anolectivo,'Classe_id'=>$classe])
		->select(
		'nomeCurso','Curso_id as id')
		->distinct()
		->get('Curso_id');
        return response()->json(['Cursos'=> $curso]);

    }


/**
 * lista de periodo do professor de um determinado anolectivo 
 *
 * @OA\Get (
 *     path="/api/Professor/proffilterperiodo/anolectivo/{ano}/classe/{classe}/curso/{curso}",
 *     tags={"Professor/Filtro"},
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
 *         name="classe",
 *         in="path",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
  *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="curso",
 *         in="path",
 *         required=true,
 *         description="ID da curso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Periodos",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomePeriodo", type="string",example="Tarde"),
*                 @OA\Property(property="id", type="integer",example=2),
 *             )
 *         )
 *     )
 * )
 */
    public function proffilterperiodo($anolectivo,$classe,$curso)
    {
 
		$professorId = Auth::id();
		$periodo = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
         ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
		->where(['Professor_id'=>$professorId,'Anolectivo_id'=>$anolectivo,'Classe_id'=>$classe,'Curso_id'=>$curso])
		->select(
		'nomePeriodo','Periodo_id as id')
		->distinct()
		->get('Periodo_id');
        return response()->json(['periodo'=> $periodo]);
    }

/**
 * lista do turma do professor de um determinado anolectivo 
 *
 * @OA\Get (
 *     path="/api/Professor/proffilterturma/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}",
 *     tags={"Professor/Filtro"},
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
 *         name="classe",
 *         in="path",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
  *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="curso",
 *         in="path",
 *         required=true,
 *         description="ID da curso",
 *         @OA\Schema(type="integer")
 *     ),
  *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="periodo",
 *         in="path",
 *         required=true,
 *         description="ID da periodo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Turmas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="id", type="integer",example=4),
 *             )
 *         )
 *     )
 * )
 */


    public function proffilterturma($anolectivo,$classe,$curso,$periodo)
    {
 
		$professorId = Auth::id();
		$turma = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
        ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
		->where(['Professor_id'=>$professorId,'Anolectivo_id'=>$anolectivo,'Classe_id'=>$classe,'Curso_id'=>$curso,'Periodo_id'=>$periodo])
		->select(
		'nomeTurma','Turma_id as id')
		->distinct()
		->get('Turma_id');
        return response()->json(['Turmas'=> $turma]);
    }





/**
 * lista do sala do professor de um determinado anolectivo 
 *
 * @OA\Get (
 *     path="/api/Professor/proffiltersala/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}/turma/{turma}",
 *     tags={"Professor/Filtro"},
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
 *         name="classe",
 *         in="path",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
  *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="curso",
 *         in="path",
 *         required=true,
 *         description="ID da curso",
 *         @OA\Schema(type="integer")
 *     ),
  *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="periodo",
 *         in="path",
 *         required=true,
 *         description="ID da periodo",
 *         @OA\Schema(type="integer")
 *     ),
   *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="turma",
 *         in="path",
 *         required=true,
 *         description="ID da turma",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Salas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="id", type="integer",example=9),
 *             )
 *         )
 *     )
 * )
 */

    public function proffiltersala($anolectivo,$classe,$curso,$periodo,$turma)
    {
 
		$professorId = Auth::id();
		$sala = DB::table('disciplinaparaclasse')
		->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
        ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
		->where([
			'Professor_id'=>$professorId,
			'Anolectivo_id'=>$anolectivo,
			'Classe_id'=>$classe,
			'Curso_id'=>$curso,
			'Periodo_id'=>$periodo,
		    'Turma_id'=>$turma
		])
		->select(
		'nomeSala','Sala_id as id')
		->distinct()
		->get('Turma_id');
        return response()->json(['Sala'=> $sala]);
    }


/**
 * @OA\Get(
 *     path="/api/Professor/proffdisciplina/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}/turma/{turma}",
 *     tags={"Professor"},
 *     summary="Obter disciplinas atribuídas ao professor",
 *     description="Endpoint para obter disciplinas atribuídas a um professor com base nos parâmetros fornecidos",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="anolectivo",
 *         in="query",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="classe",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="curso",
 *         in="query",
 *         required=true,
 *         description="ID do curso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="periodo",
 *         in="query",
 *         required=true,
 *         description="ID do período",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="turma",
 *         in="query",
 *         required=true,
 *         description="ID da turma",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Disciplinas atribuídas ao professor obtidas com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="disciplina", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="nomeDisciplina", type="string"),
 *                 )
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string"),
 *         )
 *     )
 * )
 */



        public function proffdisciplina($anolectivo,$classe,$curso,$periodo,$turma)
    {
 
		$professorId = Auth::id();
		$disciplina = DB::table('disciplinaparaclasse')
		->join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
		->where([
			'Professor_id'=>$professorId,
			'Anolectivo_id'=>$anolectivo,
			'Classe_id'=>$classe,
			'Curso_id'=>$curso,
			'Periodo_id'=>$periodo,
		    'Turma_id'=>$turma
		])
		 ->select(
		'disciplinaparaclasse.Disciplina_id as id','nomeDisciplina')
		->get();
       return response()->json(['disciplina'=> $disciplina]);
    }




}
