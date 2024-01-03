<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class ListaUsuariosController extends Controller
{
    //








/**
 * Listar Usuários
 *
 * @OA\Get(
 *     path="/api/Admin/listarUsuarios",
 *     tags={"Usuarios"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuários",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listarUsuarios", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="TelefoneAlternativo", type="string", example="123456789"),
 *                     @OA\Property(property="NumerodoTelefone", type="string", example="987654321"),
 *                     @OA\Property(property="dataofbirth", type="string", format="date", example="1990-01-01"),
 *                     @OA\Property(property="banned_until", type="string", format="datetime", example="2023-01-01 12:00:00"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="usertype", type="string", example="admin"),
 *                     @OA\Property(property="full_name", type="string", example="John Doe"),
 *                     @OA\Property(property="idade", type="integer", example=30),
 *                 )
 *             )
 *         )
 *     )
 * )
 */
 

public function listarUsuarios(){




$listarUsuarios = User::select(
'id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade")
)
// ->whereHas('roles', function ($query) {
// $query->whereNotIn('id', [4, 3]);
// })
->get();

 


return response()->json(['listarUsuarios' => $listarUsuarios], 200);


}


/**
 * Lista Encarregados
 *
 * @OA\Get(
 *     path="/api/Admin/listaEncarregado",
 *     tags={"Usuarios"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de encarregados",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listaEncarregados", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="TelefoneAlternativo", type="string", example="123456789"),
 *                     @OA\Property(property="NumerodoTelefone", type="string", example="987654321"),
 *                     @OA\Property(property="dataofbirth", type="string", format="date", example="1990-01-01"),
 *                     @OA\Property(property="banned_until", type="string", format="datetime", example="2023-01-01 12:00:00"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="usertype", type="string", example="encarregado"),
 *                     @OA\Property(property="full_name", type="string", example="John Doe"),
 *                     @OA\Property(property="idade", type="integer", example=30),
 *                 )
 *             )
 *         )
 *     )
 * )
 */
 


public function listaEncarregado(){


$listaEncarregado = User::select(
'id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade")
)
->whereHas('roles', function ($query) {
$query->where('id', 3);
})
->get();


return response()->json(['listaEncarregados' => $listaEncarregado], 200);


}


/**
 * Lista Professores
 *
 * @OA\Get(
 *     path="/api/Admin/listaProfessores",
 *     tags={"Usuarios"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de professores",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listaProfessores", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="TelefoneAlternativo", type="string", example="123456789"),
 *                     @OA\Property(property="NumerodoTelefone", type="string", example="987654321"),
 *                     @OA\Property(property="dataofbirth", type="string", format="date", example="1990-01-01"),
 *                     @OA\Property(property="banned_until", type="string", format="datetime", example="2023-01-01 12:00:00"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="usertype", type="string", example="professor"),
 *                     @OA\Property(property="full_name", type="string", example="John Doe"),
 *                     @OA\Property(property="idade", type="integer", example=30),
 *                 )
 *             )
 *         )
 *     )
 * )
 */
 

public function listaProfessores(){


$listaProfessores = User::select(
'id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade")
)
->whereHas('roles', function ($query) {
$query->where('id', 2);
})
->get();


return response()->json(['listaProfessores' => $listaProfessores], 200);


}

/**
 * Listar Funcionários
 *
 * @OA\Get(
 *     path="/api/Admin/listarFuncionarios",
 *     tags={"Usuarios"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de funcionários",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listaFuncionarios", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="TelefoneAlternativo", type="string", example="123456789"),
 *                     @OA\Property(property="NumerodoTelefone", type="string", example="987654321"),
 *                     @OA\Property(property="dataofbirth", type="string", format="date", example="1990-01-01"),
 *                     @OA\Property(property="banned_until", type="string", format="datetime", example="2023-01-01 12:00:00"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="usertype", type="string", example="funcionario"),
 *                     @OA\Property(property="full_name", type="string", example="John Doe"),
 *                     @OA\Property(property="idade", type="integer", example=30),
 *                 )
 *             )
 *         )
 *     )
 * )
 */
 

public function listarFuncionarios(){


$listaEncarregado = User::select(
'id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade")
)
->whereHas('roles', function ($query) {
$query->where('id', 5);
})
->get();


return response()->json(['listaEncarregados' => $listaEncarregado], 200);


}

/**
 * @OA\Get(
 *     path="/api/Admin/matriculados",
 *     summary="lista de Estudantes Matriculados",
 *     tags={"Matriculados"},
 *     @OA\Response(
 *         response=200,
 *         description="Operação bem-sucedida",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listaestudantematriculados", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="TelefoneAlternativo", type="string", description="Alternative phone number"),
 *                 @OA\Property(property="NumerodoTelefone", type="string", description="Phone number"),
 *                 @OA\Property(property="dataofbirth", type="string", description="Date of birth"),
 *                 @OA\Property(property="banned_until", type="string", description="Banned until date"),
 *                 @OA\Property(property="email", type="string", description="Email address"),
 *                 @OA\Property(property="usertype", type="string", description="User type"),
 *                 @OA\Property(property="full_name", type="string", description="Full name of the student"),
 *                 @OA\Property(property="idade", type="integer", description="Age of the student")
 *             ))
 *         )
 *     )
 * )
 */

 
public function matriculados(){

$estudantesmatriculados = User::select(
    'estudante_x_ano_x_classe.student_id', // Include the column you want to be distinct
    'users.id as id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype','reg_Numero',
    DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
    DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade")
)
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
->whereHas('roles', function ($query) {
    $query->where('id', 4);
})
->distinct() // Use the DISTINCT keyword
->get();


return response()->json(['listaestudantematriculados' =>$estudantesmatriculados], 200);

	
}













/**
 * @OA\Get(
 *     path="/api/Admin/Naomatriculados",
 *     summary="Obtenha uma lista de alunos não matriculados",
 *     tags={"Não Matriculados"},
 *     @OA\Response(
 *         response=200,
 *         description="Operação bem-sucedida",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="listaestudantesnaomatriculados", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="TelefoneAlternativo", type="string", description="Alternative phone number"),
 *                 @OA\Property(property="NumerodoTelefone", type="string", description="Phone number"),
 *                 @OA\Property(property="dataofbirth", type="string", description="Date of birth"),
 *                 @OA\Property(property="banned_until", type="string", description="Banned until date"),
 *                 @OA\Property(property="email", type="string", description="Email address"),
 *                 @OA\Property(property="usertype", type="string", description="User type"),
 *                 @OA\Property(property="full_name", type="string", description="Full name of the student"),
 *                 @OA\Property(property="idade", type="integer", description="Age of the student")
 *             ))
 *         )
 *     )
 * )
 */





public function Naomatriculados(){
$Naoestudantesmatriculados = User::select('users.id as id', 'TelefoneAlternativo', 'NumerodoTelefone', 'dataofbirth', 'banned_until', 'email','dataofbirth','usertype','reg_Numero',
    DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
    DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade"))
    ->leftJoin('estudante_x_ano_x_classe', function ($join) {
        $join->on('estudante_x_ano_x_classe.student_id', '=', 'users.id');
    })
    ->whereNull('estudante_x_ano_x_classe.student_id')
		->whereHas('roles', function ($query) {
		$query->where('id', 4);
		})
    ->get();


return response()->json(['Naoestudantesmatriculados' =>$Naoestudantesmatriculados], 200);



}




}
