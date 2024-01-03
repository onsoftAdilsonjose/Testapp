<?php

namespace App\Http\Controllers\UPDATEINFO;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstudanteEncarregadoRequest;
use App\Jobs\SendEmailMatriculaJob;
use App\Jobs\SendSmsMatriculaJob;
use App\Models\Pessoa;
use App\Models\Role;
use App\Models\User;
use App\MyCustomFuctions\Customised;
use App\MyCustomFuctions\MatricularEstudante;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
class Registroestudantesemsalacontroller extends Controller
{
    //

/**
 * @OA\Post(
 *     path="/api/Admin/RegistrarEstudanteSemClasse",
 *     tags={"Registro de Estudante Sem Sala"},
 *     summary="Criar estudante e encarregado mas sem atribuir uma sala",
 *     description="Endpoint para criar um novo estudante e encarregado sem atribuir uma sala",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do estudante e encarregado",
 *         @OA\JsonContent(
 *             required={"estudante", "encarregado"},
 *             @OA\Property(property="estudante", type="object",
 *                 required={"primeiroNome", "ultimoNome", "nomePai", "nomeMae", "generoId", "dataofbirth", "numeroDoDocumento", "tipoDeDocumento", "pais", "provincia", "municipio", "telefone", "email"},
 *                 @OA\Property(property="primeiroNome", type="string"),
 *                 @OA\Property(property="ultimoNome", type="string"),
 *                 @OA\Property(property="nomePai", type="string"),
 *                 @OA\Property(property="nomeMae", type="string"),
 *                 @OA\Property(property="generoId", type="integer"),
 *                 @OA\Property(property="dataofbirth", type="string", format="date"),
 *                 @OA\Property(property="numeroDoDocumento", type="string"),
 *                 @OA\Property(property="tipoDeDocumento", type="string"),
 *                 @OA\Property(property="pais", type="integer"),
 *                 @OA\Property(property="provincia", type="integer"),
 *                 @OA\Property(property="municipio", type="integer"),
 *                 @OA\Property(property="telefone", type="string"),
 *                 @OA\Property(property="email", type="string")
 *             ),
 *             @OA\Property(property="encarregado", type="object",
 *                 required={"primeiroNome", "ultimoNome", "telefoneEncarregado", "email"},
 *                 @OA\Property(property="primeiroNome", type="string"),
 *                 @OA\Property(property="ultimoNome", type="string"),
 *                 @OA\Property(property="telefoneEncarregado", type="string"),
 *                 @OA\Property(property="email", type="string")
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estudante e encarregado criados com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="dadosPessoais", type="object",
 *                 @OA\Property(property="nomeCompleto", type="string"),
 *                 @OA\Property(property="dataDenascimento", type="string", format="date"),
 *                 @OA\Property(property="numeroDocumento", type="string"),
 *                 @OA\Property(property="contact", type="string"),
 *                 @OA\Property(property="genero", type="string"),
 *                 @OA\Property(property="nacionalidade", type="string"),
 *                 @OA\Property(property="processo", type="string")
 *             ),
 *             @OA\Property(property="encarregados", type="object",
 *                 @OA\Property(property="nomeCompleto", type="string"),
 *                 @OA\Property(property="contact", type="string"),
 *                 @OA\Property(property="email", type="string")
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string"),
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

public function RegistrarEstudanteSemClasse(Request $request)
{



    DB::beginTransaction();
    try {
        $estudanteData = $request->estudante;
        $encarregadoData = $request->encarregado;
        $EncarregadoregNumero = MinhasFuncoes::RegNumero($Encarregado->name);
        $EstudanteregNumero = MinhasFuncoes::RegNumero($Estudante->name);

        // Dados dos Estudantes
        $primeiroNome = $estudanteData['primeiroNome'];
        $ultimoNome = $estudanteData['ultimoNome'];
        $nomePai = $estudanteData['nomePai'];
        $nomeMae = $estudanteData['nomeMae'];
        $generoId = $estudanteData['generoId'];
        $dataofbirth = $estudanteData['dataofbirth'];
        $numeroDoDocumento = $estudanteData['numeroDoDocumento'];
        $tipoDeDocumento = $estudanteData['tipoDeDocumento'];
        $pais = $estudanteData['pais'];
        $provincia = $estudanteData['provincia'];
        $municipio = $estudanteData['municipio'];
        $telefone = $estudanteData['telefone'];
        $email = $estudanteData['email'];


        // Dados dos Encarregados
        $encarregadoPrimeiroNome = $encarregadoData['primeiroNome'];
        $encarregadoUltimoNome = $encarregadoData['ultimoNome'];
        $telefoneEncarregado = $encarregadoData['telefoneEncarregado'];
        $encarregadoEmail = $encarregadoData['email'];

        $Pessoa = Pessoa::create([
            'tipoDeDocumento' => $tipoDeDocumento,
            'numeroDoDocumento' => $numeroDoDocumento,
            'reg_Numero' => $EstudanteregNumero,
            'pais' => $pais,
            'municipio_id' => $municipio,
            'provincia_id' => $provincia,
            'genero_id' => $generoId,
        ]);

        $encarregado = new User([
            'primeiro_nome' => $encarregadoPrimeiroNome,
            'ultimo_nome' => $encarregadoUltimoNome,
            'email' => $encarregadoEmail,
            'reg_Numero' => $EncarregadoregNumero,
            'usertype' => $Encarregado->name,
            'password' => Hash::make($EncarregadoregNumero),
            'numeroDotelefone' => $telefoneEncarregado,
        ]);
        $encarregado->save();
        $encarregado->roles()->attach($Encarregado->id);


        $estudante = new User([
            'pessoa_id' => $Pessoa->id, 
            'primeiro_nome' => $primeiroNome,
            'ultimo_nome' => $ultimoNome,
            'email' => $email,
            'reg_Numero' => $EstudanteregNumero,
            'usertype' => $Estudante->name,
            'password' => Hash::make($EstudanteregNumero),
            'nomePai' => $nomePai,
            'nomeMae' => $nomeMae,
            'dataofbirth' => $dataofbirth,
            'numeroDotelefone' => $telefone,
            'encarregadoID' => $encarregado->id, 
        ]);
        $estudante->save();
        $estudante->roles()->attach($Estudante->id);

   
     

        $dadosPessoais = [
        'nomeCompleto' => $primeiroNome . ' ' . $ultimoNome,
        'dataDenascimento' => $dataofbirth,
        'numeroDocumento' => $numeroDoDocumento,
        'contact' => $telefone,
        'genero' =>Customised::Genero($generoId),
        'nacionalidade' =>Customised::Paises($pais),
        'processo'=>$EstudanteregNumero
        ];

        $encarregados = [
        'nomeCompleto' => $encarregadoPrimeiroNome . ' ' . $encarregadoUltimoNome,
        'contact' => $encarregadoEmail,
        'email' => $encarregadoEmail,
        //'nacionalidade' => 'Angolano',
        ];

        DB::commit();
        return response()->json([
             'dadosPessoais'=>$dadosPessoais,
             'encarregados'=>$encarregados,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 422);
    }
}


/**
 * @OA\Post(
 *     path="/api/Admin/ActualizarDadosUsuarios/{id}",
 *     summary="Atualizar dados de Usuario",
 *     description="Atualiza os dados do Usuario com o ID especificado",
 *     tags={"Update Usuario"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do Usuario a ser atualizado",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados a serem atualizados",
 *         @OA\JsonContent(
 *             required={"primeiro_nome", "ultimo_nome", "email", "nomePai", "nomeMae", "dataofbirth", "telefone", "tipoDeDocumento", "numeroDoDocumento", "pais", "municipio", "provincia", "generoId"},
 *             @OA\Property(property="primeiro_nome", type="string"),
 *             @OA\Property(property="ultimo_nome", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="nomePai", type="string"),
 *             @OA\Property(property="nomeMae", type="string"),
 *             @OA\Property(property="dataofbirth", type="string", description="Data de nascimento no formato YYYY-MM-DD"),
 *             @OA\Property(property="telefone", type="string"),
 *             @OA\Property(property="tipoDeDocumento", type="string"),
 *             @OA\Property(property="numeroDoDocumento", type="string"),
 *             @OA\Property(property="pais", type="integer"),
 *             @OA\Property(property="municipio", type="integer"),
 *             @OA\Property(property="provincia", type="integer"),
 *             @OA\Property(property="generoId", type="integer"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Dados atualizados com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Dados atualizados com sucesso")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 * )
 */

public function ActualizarDadosUsuarios(Request $request, $id)
{
    // Validation rules
    $rules = [
        'primeiro_nome' => 'required|string',
        'ultimo_nome' => 'required|string',
        'email' => 'sometimes|email|unique:users,email,' . $id,
        'nomePai' => 'required|string',
        'nomeMae' => 'required|string',
        'dataofbirth' => 'required|date',
        'telefone' => 'sometimes|string',
        'tipoDeDocumento' => 'required|string',
        'numeroDoDocumento' => 'required|string',
        'pais' => 'sometimes|integer',
        'municipio' => 'sometimes|integer',
        'provincia' => 'sometimes|integer',
        'generoId' => 'sometimes|integer',
    ];

    // Validation messages
    $messages = [
        'unique' => 'O :attribute já está em uso.',
    ];

    // Run the validator
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();

    try {
        $estudante = User::find($id);
        $estudante->update([
            'primeiro_nome' => $request->primeiro_nome,
            'ultimo_nome' => $request->ultimo_nome,
            'email' => $request->email,
            'nomePai' => $request->nomePai,
            'nomeMae' => $request->nomeMae,
            'dataofbirth' => $request->dataofbirth,
            'telefone' => $request->telefone,
            // Add other fields you want to update for estudante here
        ]);

     if ($estudante->pessoa_id) {
        $pessoa = Pessoa::find($estudante->pessoa_id);
        $pessoa->update([
        'tipoDeDocumento' => $request->tipoDeDocumento,
        'numeroDoDocumento' => $request->numeroDoDocumento,
        'pais' => $request->pais,
        'municipio_id' => $request->municipio,
        'provincia_id' => $request->provincia,
        'genero_id' => $request->generoId,
        ]);
     }

        DB::commit();
        return response()->json([
            'message' => 'Dados atualizados com sucesso',
            // Add any other response data you want to include
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json(['error' => $e->getMessage()], 422);
    }
}










public function EstudanteInfor($reg_Numero){

$EstudanteConfirmacao= DB::table('estudante_x_ano_x_classe')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->where(['users.status' => 0])
 ->where(['users.reg_Numero'=>$reg_Numero])
->select(
    'users.id',
    'users.reg_Numero',
    'users.primeiro_nome',
    'users.ultimo_nome',
    'users.dataofbirth',
    'nomeCurso',
    'nomePeriodo',
    'nomeTurma',
    'nomeSala',
    'classe_name',
    'ano_lectivo',
    'ano_lectivos.id as Anolectivo_id','classes.id as Classe_id'
)
->first();


 if (!$EstudanteConfirmacao) {
        return response()->json(['error' => 'Aluno não encontrado or por favor Verificar O numero de Registro do Estudante'], 404);
    }

 return response()->json(['EstudanteConfirmacao' => $EstudanteConfirmacao], 200);




}




}
