<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Biblioteca;
use App\Helpers\Docs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Estudante\EstudanteInfounico;

class EstudanteBibliotecaController extends Controller
{
    //





/**
 * lista dos Livro de uma determinada disciplina 
 *
 * @OA\Get (
 *     path="/api/Estudante/biblioteca/anolectivo/{anolectivo}/disciplina/{disciplinaid}",
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
 *         name="disciplinaid",
 *         in="path",
 *         required=true,
 *         description="ID da Disciplina",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista dos Livros de uma determinada disciplina",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="id", type="integer",example=13),
*                 @OA\Property(property="livronome", type="string",example="Ciencias Integrais"),
*                 @OA\Property(property="nomeCurso",type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="author", type="string",example="adilson jose"),
*                 @OA\Property(property="book_pdf", type="string",example="http://127.0.0.1:8000/storage/products/1700729798.pdf"),
*                 @OA\Property(property="AdicionouLivro", type="string",example="Adimin Admin"),
 *             )
 *         )
 *     )
 * )
 */




            public function BibliotecaEstudante($ano,$disciplinaid)
    {
 

$userId = Auth::id();
$classe = EstudanteInfounico::getstudentInfo($ano,$userId);
$Biblioteca = DB::table('biblioteca')
->join('curso', 'curso.id', '=', 'biblioteca.Curso_id')
->join('classes', 'classes.id', '=', 'biblioteca.Classe_id')
->join('users', 'users.id', '=', 'biblioteca.userid')
->select('biblioteca.id as id','livronome','nomeCurso','classe_name', 'author', 'book_pdf',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS AdicionouLivro"))
->where(['biblioteca.Disciplina_id'=>$disciplinaid,'Classe_id'=>$classe->Classe_id,'Curso_id'=>$classe->Curso_id])
->get();

        return response()->json(['Biblioteca' => $Biblioteca]);

    }
}


       