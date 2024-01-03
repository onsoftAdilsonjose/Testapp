<?php

namespace App\Http\Controllers;


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
use App\Estudante\EstudanteInfounico;




class RegisterContoller extends Controller
{




public function createUserWithRole(Request $request)
{


 //return "its here";

    try {
        DB::beginTransaction();
        
        // Validate the incoming request data (you can customize the validation rules as needed)
        $request->validate([
                'primeiroNome' => 'required|string|max:30',
                'ultimoNome' => 'required|string|max:30',
                'email' => 'required|string|email|unique:users|max:100',
                'role' => 'required|string|exists:roles,id',
                'pais' => 'required|string|exists:paises,id',
                'provinciaId' => 'required|string|exists:provincias,id',
                'municipioId' => 'required|string|exists:municipios,id',
                'generoId' => 'required|integer',
                'nomePai' => 'required|string|max:55',
                'nomeMae' => 'required|string|max:55',
                'dataofbirth' => 'required|date',
                'numeroDeTelefone' => 'required|string|max:20',
                'telefoneAlternativo' => 'required|string|max:20',
                'tipoDeDocumento' => 'required|string|max:50',
                'numeroDocumento' => 'required|string|max:20',
        ]);
        
         $role = Role::select('name','id')->where('id', $request->input('role'))->first();
        // Get the role's registration number using the MinhasFuncoes::RegNumero() function
         $RegNumero = MinhasFuncoes::RegNumero($role->name);

        
        // Create a new Pessoa with the provided data
        $Pessoa = Pessoa::create([
            'BoletimdeNascimento' => $request->input('boletimDeNascimento'), 
            'reg_Numero' => $RegNumero,
            'avatar' => $request->input('avatar'),
            'pais' => $request->input('pais'),
            'municipio_id' => $request->input('municipioId'),
            'provincia_id' => $request->input('provinciaId'),
            'num_cedula' => $request->input('numeroCedula'),
            'bairro' => $request->input('bairro'),
            'n_passaport' => $request->input('numeroPassaport'),
            'genero_id' => $request->input('generoId'),
            'num_bilhete' => $request->input('numeroBilhete'),
        ]);
        

            $user = new User([
            'pessoa_id' => $Pessoa->id,
            'primeiro_nome' => $request->input('primeiroNome'),
            'ultimo_nome' => $request->input('ultimoNome'),
            'email' => $request->input('email'),
            'reg_Numero' => $RegNumero,
            'usertype' => $role->name,
            'password' => Hash::make($RegNumero),
            'nomePai' => $request->input('nomePai'),
            'nomeMae' => $request->input('nomeMae'),
            'dataofbirth' => $request->input('dataofbirth'),
            'NumerodoTelefone' => $request->input('numeroDeTelefone'),
            'TelefoneAlternativo' => $request->input('telefoneAlternativo'),
             ]);
             $user->save();

 
             /// estes dados serao guardados para gearar o pdf 
             $RegistrationEstudante = [
              'Estudante' => $user,
              'DadosRelacionados' => $Pessoa,


             ];
          
         
          
        // Fetch the role based on the provided role name
       
        
        // Save the association between user and role in the role_user (pivot) table
        $user->roles()->attach($role->id);//
       // $SmsMarketin = MinhasFuncoes::SmsMarketing($RegNumero);
       // $EmailMarketin = MinhasFuncoes::generatePDF($RegistrationEstudante);
        DB::commit();
        return response()->json(['message' => 'Usuário criado com sucesso com função de ' .''. $role->name,
           // 'SmsMarketin'=>$SmsMarketin,
            // 'EmailMarketin'=>$EmailMarketin,
            'status'=>200,
          
        ],200);

    } catch (ValidationException $e) {
        // If validation fails, return the validation errors as a JSON response
        DB::rollBack();

      $firstError = null;
       foreach ($e->errors() as $fieldErrors) {
        if (count($fieldErrors) > 0) {
            $firstError = $fieldErrors[0];
            break;
        }
    }






        return response()->json([

            'errors' => $firstError,
            'status'=>422,



    ], 422);
    } catch (\Exception $e) {
        // Handle any other unexpected exceptions if needed
        DB::rollBack();
        return response()->json([
            'errors' => 'Error creating user with role',
            'status'=>422,


        ], 422);
    }
}


 















// MatricularEstudante::CriarNotas($student_id,$Anolectivo_id,$Periodo_id,$Turma_id,$Sala_id,$Classe_id,$Curso_id)
public function RegistrarEstudante(EstudanteEncarregadoRequest $request)
{



    DB::beginTransaction();

  

    try {
        $estudanteData = $request->estudante;
        $encarregadoData = $request->encarregado;
        $Estudante = Role::select('name', 'id')->where('id', 4)->first();
       
        $ano_lectivos = DB::table('ano_lectivos')->first();
     
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

        // Dados de Matricula
        $cursoId = $estudanteData['cursoId'];
        $classeId = $estudanteData['classeId'];
        $peridoId = $estudanteData['peridoId'];
        $salaId = $estudanteData['salaID'];
        $turmaId = $estudanteData['turmaID'];


 
        $Pessoa = Pessoa::create([
            'tipoDeDocumento' => $tipoDeDocumento,
            'numeroDoDocumento' => $numeroDoDocumento,
            'reg_Numero' => $EstudanteregNumero,
            'pais' => $pais,
            'municipio_id' => $municipio,
            'provincia_id' => $provincia,
            'genero_id' => $generoId,
        ]);





        if (array_key_exists('id', $encarregadoData)) {
        $encarregadoid = $encarregadoData['id'];
        $Encarregado = User::Leftjoin('pessoa','.pessoa.id','=','users.pessoa_id') 
        ->select(
        'primeiro_nome',
        'ultimo_nome',
        'email',
        'users.id as id',
        'users.reg_Numero',
        'usertype',
        'numeroDotelefone','pais')
        ->where(['users.id'=>$encarregadoid])->first();
 
$encarregadoid = $Encarregado->id;
$encarregadoPrimeiroNome=$Encarregado->primeiro_nome ;
$encarregadoUltimoNome= $Encarregado->ultimo_nome;
$encarregadoEmail=$Encarregado->email;
$EncarregadoregNumero =$Encarregado->reg_Numero;
$telefoneEncarregado =$Encarregado->numeroDotelefone;
$encarenacionalidade =Customised::Paises($Encarregado->pais);
//$encarenacionalidade = ($encarenacionalidade == null) ? 'Uknown' : $encarenacionalidade ;

} else {
$Encarregado = Role::select('name', 'id')->where('id', 3)->first();   
$EncarregadoregNumero = MinhasFuncoes::RegNumero($Encarregado->name);
$encarregadoPrimeiroNome = $encarregadoData['primeiroNome'];
$encarregadoUltimoNome = $encarregadoData['ultimoNome'];
$telefoneEncarregado = $encarregadoData['telefoneEncarregado'];
$encarregadoEmail = $encarregadoData['email'];
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
$encarregadoid = $encarregado->id;

 
}







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
            'encarregadoID' => $encarregadoid, 
        ]);
        $estudante->save();
        $estudante->roles()->attach($estudante->id);




        // $EstudanteInfounico = EstudanteInfounico::EsteAlunojamatriculado($estudante->id,$ano_lectivos);
        // if ($EstudanteInfounico !==null) {
        //     return $EstudanteInfounico ;
        // }


        MatricularEstudante::MatricularOrconfirmar($estudante->id,$peridoId,$classeId,$cursoId,$salaId,$turmaId);

        $getTodasDisciplinas = MatricularEstudante::getTodasDisciplinas($ano_lectivos->id,$classeId,$peridoId,$turmaId,$salaId,$cursoId);

        MatricularEstudante::DisciplinaParaAluno($getTodasDisciplinas,$estudante->id);

        $mensalidadeId = Pagamento::SingleStudentDetalhes($classeId,$ano_lectivos->id,$estudante->id);
        $dadosCademico = MatricularEstudante::MatriculadeAluno($mensalidadeId);
        $dadosCademico->estudanteid = $estudante->id;

   
        //$dadosPessoais = Customised::dadosPessoais();
        //$dadosEncarregado = Customised::dadosEncarregado();

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
        'contact' => $telefoneEncarregado,
        'email' => $encarregadoEmail,
        'nacionalidade' =>'Angolano',
        ];


// return $encarregados;

//send by email
// $sendEmailJob = new SendEmailMatriculaJob($RegistrationEstudante);
// dispatch($sendEmailJob);

//send by watsap
// $SendSmsMatriculaJob = new SendSmsMatriculaJob($RegistrationEstudante);
// dispatch($SendSmsMatriculaJob);

 



        DB::commit();
        return response()->json([
             'dadosPessoais'=>$dadosPessoais,
             'dadosCademico'=> $dadosCademico,
              'encarregados'=>$encarregados,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 422);
    }
}




public function RegistrarEncarregado(Request $request)
{
    try {
        DB::beginTransaction();
        
        // Validate the incoming request data (you can customize the validation rules as needed)
        $request->validate([
            'primeiroNome' => 'required|string|max:25',
            'ultimoNome' => 'required|string|max:25',
            'email' => 'required|string|email|unique:users|max:255',
            // 'password' => 'required|string|min:8',
             //'role' => 'required|string|exists:roles,id'
        ]);
         $role = Role::select('name','id')->where('id', 3)->first();
        // Get the role's registration number using the MinhasFuncoes::RegNumero() function
         $RegNumero = MinhasFuncoes::RegNumero($role->name);
        
        // Create the user

        
        // Create a new Pessoa with the provided data
        $Pessoa = Pessoa::create([
            'BoletimdeNascimento' => $request->input('boletimDeNascimento'), 
            'reg_Numero' => $RegNumero,
            'avatar' => $request->input('avatar'),
            'pais' => $request->input('pais'),
            'municipio_id' => $request->input('municipioId'),
            'provincia_id' => $request->input('provinciaId'),
            'num_cedula' => $request->input('numeroCedula'),
            'bairro' => $request->input('bairro'),
            'n_passaport' => $request->input('numeroPassaport'),
            'genero_id' => $request->input('generoId'),
            'num_bilhete' => $request->input('numeroBilhete'),
        ]);
        

            $user = new User([
            'pessoa_id' => $Pessoa->id,
            'primeiro_nome' => $request->input('primeiroNome'),
            'ultimo_nome' => $request->input('ultimoNome'),
            'email' => $request->input('email'),
            'reg_Numero' => $RegNumero,
            'usertype' => $role->name,
            'password' => Hash::make($RegNumero),
            'nomePai' => $request->input('nomePai'),
            'nomeMae' => $request->input('nomeMae'),
            'dataofbirth' => $request->input('dataofbirth'),
            'NumerodoTelefone' => $request->input('numeroDeTelefone'),
            'TelefoneAlternativo' => $request->input('telefoneAlternativo'),
             ]);
             $user->save();


             /// estes dados serao guardados para gearar o pdf 
             $RegistrationEstudante = [
              'Estudante' => $user,
              'DadosRelacionados' => $Pessoa,


             ];
          
         
          
        // Fetch the role based on the provided role name
       
        
        // Save the association between user and role in the role_user (pivot) table
        $user->roles()->attach($role->id);//

       // $SmsMarketin = MinhasFuncoes::SmsMarketing($RegNumero); 

   
       // $EmailMarketin = MinhasFuncoes::generatePDF($RegistrationEstudante);
        DB::commit();
        return response()->json(['message' => 'Usuário criado com sucesso com função de ' .''. $role->name,
            //'SmsMarketin'=>$SmsMarketin,
            // 'EmailMarketin'=>$EmailMarketin,
            'status'=>200,
          
        ],200);

    } catch (ValidationException $e) {
        // If validation fails, return the validation errors as a JSON response
        DB::rollBack();
        return response()->json([

            'errors' => $e->errors(),
            'status'=>422,



    ], 422);
    } catch (\Exception $e) {
        // Handle any other unexpected exceptions if needed
        DB::rollBack();
        return response()->json([
            'message' => 'Error creating user with role',
            'status'=>422,


        ], 422);
    }
}












}
