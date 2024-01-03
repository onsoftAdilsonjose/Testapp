<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Estudante_x_Ano_x_Classe;
use Throwable;
use DB;
use App\MyCustomFuctions\Customised;

class EstudanteDadosestudanteController extends Controller
{
    //


/**
 * Detalhes do Estudante da Classe Actual
 *
 * @OA\Get (
 *     path="/api/Estudante/dadosdoestudantelogado",
 *     tags={"Estudante"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Dados Detalhados de Current Anolectivo",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="telefoneAlternativo", type="string",example="926551976"),
*                 @OA\Property(property="numeroDotelefone", type="string",example="915882240"),
*                 @OA\Property(property="nomeMae",type="string",example="Makiande Jose Kiala"),
*                 @OA\Property(property="nomePai", type="string",example="Nzilau Fernando Miguel"),
*                 @OA\Property(property="municipio_id", type="string",example="Maianga"),
*                 @OA\Property(property="pais", type="string",example="Angola"),
*                 @OA\Property(property="provincia_id", type="string",example="Luanda"),
*                 @OA\Property(property="genero_id", type="string",example="Masculino"),
 *             )
 *         )
 *     )
 * )
 */

public function dadosdoestudante(){



$userId = Auth::id();


$dadosestudante= Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->join('pessoa', 'pessoa.id', '=', 'users.pessoa_id')
->where([
'users.id' => $userId,
'estudante_x_ano_x_classe.student_id' => $userId,
])
->select(	
	'telefoneAlternativo',
	'numeroDotelefone',
	'nomeMae',
	'nomePai',
	'municipio_id',
	'pais',
	'provincia_id',
	'genero_id',
	'dataofbirth', 
	'curso.nomeCurso',
	'classes.classe_name',
	'salas.nomeSala',
	'turmas.nomeTurma',
	'periodos.nomePeriodo',
	'ano_lectivos.ano_lectivo',
    )
->first();

//$Genero = ustomised::Genero($dadosestudante->genero_id);



return response()->json(['dadosestudante' => $dadosestudante], 200);




    }
}
