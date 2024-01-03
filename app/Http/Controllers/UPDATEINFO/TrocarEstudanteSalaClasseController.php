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
use App\Models\Estudante_x_Ano_x_Classe;
use App\Estudante\EstudanteInfounico;

class TrocarEstudanteSalaClasseController extends Controller
{




public function TrocarEstudante(Request $request,$estudanteid,$classeid,$anolectivo)
{



 

    //PARA TROCAR UM ESTUDANTE DE UMA UM CURSO OU UMA DISCIPLINA DIFERENTE E NECESSARTIO TER EM CONTAS OS 
    //DADOS ANTERIO THE PAGAMENTO DEVEM SER CANCELADO E SE TIVER NOTAS JA NO SISTEMA TAMBEM DEVEM SER APAGADOS 
    //E GERAR UM RELATORIO QUEM APAGOU E O MOTIVO  SO ASSIM E POSSIVEL TROCAR O MESMO DE UMA CLASSE OU MESO DE UM CURSO

    //IMPORTANTE!! SE O PAGAMENTO FOI CANCELADO NESTE CASO DEPOIS DA TROCA DEVE SER REDICIONADO PARA AREIA DE PAGAMENTO
    
    // Validate parameters
//     $validator = Validator::make([
//         'estudanteid' => $estudanteid,
//         'classeid' => $classeid,
//         'anoLectivo' => $anoLectivo,
//     ], [
//         'estudanteid' => 'required|integer',
//         'classeid' => 'required|integer',
//         'anoLectivo' => 'required|integer',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }


// [{"Periodo_id":2,"Turma_id":4,"Sala_id":2,"Curso_id":1,"Classe_id":"11"}]




DB::beginTransaction();
try {

$estudantes = Estudante_x_Ano_x_Classe::where([
'student_id'=>$estudanteid,
'Classe_id'=> $classeid,
'Anolectivo_id'=>$anolectivo
])
->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
 ->select('student_id','Classe_id','Anolectivo_id','Curso_id','estudante_x_ano_x_classe.id as id','Periodo_id','Sala_id','Turma_id')
->first();

$request->Periodo_id= (int)$request->Periodo_id;
$request->Turma_id= (int)$request->Turma_id;
$request->Sala_id= (int)$request->Sala_id;
$request->Classe_id= (int)$request->Classe_id;
$request->Curso_id= (int)$request->Curso_id;




 

  $payments = DB::table('payments')->where(['classID'=>$estudantes->Classe_id,'studentID'=>$estudantes->student_id,'anolectivoID'=>$estudantes->Anolectivo_id,'Cancelar'=> 0,'fc'=>null])->first();

if ($estudantes->Classe_id != $request->Classe_id ||  $estudantes->Curso_id != $request->Curso_id || $estudantes->Periodo_id != $request->Periodo_id) {
    if ($payments) {
     return response()->json(['error' => 'NÃO SERA POSSIVEL ALTERAR OS DADOS E NECESSARIO CANCELAR OS PAGAAMENTOS  APAGAR AS NOTAS DO ESTUDANTE'], 422);
    }
}



if ($estudantes->Turma_id == $request->Turma_id || $estudantes->Sala_id == $request->Sala_id) {
  
     return response()->json(['error' => 'O Dados Inseridos Sao Indeticos Nada Foi Alterado'], 422);
  
 




}

EstudanteInfounico::ApagarNotasAntiga($estudantes->student_id,$estudantes->Classe_id,$estudantes->Anolectivo_id);






 
  

// Find the record with the given ID using the 'Estudante_x_Ano_x_Classe' model
$Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::find($estudantes->id);

// Update the fields with values from the request
$Estudante_x_Ano_x_Classe->Periodo_id = $request->Periodo_id;
$Estudante_x_Ano_x_Classe->Turma_id = $request->Turma_id;
$Estudante_x_Ano_x_Classe->Sala_id = $request->Sala_id;
$Estudante_x_Ano_x_Classe->Curso_id = $request->Curso_id;
$Estudante_x_Ano_x_Classe->Classe_id = $request->Classe_id;

// Save the changes to the database
$Estudante_x_Ano_x_Classe->save();

// Call the 'getTodasDisciplinas' method to retrieve data based on the updated values
$getTodasDisciplinas = MatricularEstudante::getTodasDisciplinas(
    $estudantes->Anolectivo_id,
    $Estudante_x_Ano_x_Classe->Classe_id,
    $Estudante_x_Ano_x_Classe->Periodo_id,
    $Estudante_x_Ano_x_Classe->Turma_id,
    $Estudante_x_Ano_x_Classe->Sala_id,
    $Estudante_x_Ano_x_Classe->Curso_id
);


MatricularEstudante::DisciplinaParaAluno($getTodasDisciplinas,$estudantes->student_id);
 


   




$mensalidadeId = Pagamento::SingleStudentDetalhes($request->Classe_id,$estudantes->Anolectivo_id,$estudantes->student_id);


  


$estudante = MatricularEstudante::EstudanteDeAserConfirmado($estudantes->student_id);

$encarregado = MatricularEstudante::Encarregado_unico($estudantes->student_id);

$nomeCompleto = $encarregado->nomeCompleto ?? 'unknown';
$email = $encarregado->email ?? 'unknown';
$numeroDotelefone = $encarregado->numeroDotelefone ?? 'unknown';
$reg_Numero = $encarregado->reg_Numero ?? 'unknown';



   
 
        

        $dadosCademico = MatricularEstudante::ConfirmacaodeAluno($mensalidadeId);



        $dadosCademico->estudanteid = $estudantes->student_id;

       $dadosPessoais = [
       'nomeCompleto' =>$estudante->nomeCompleto,
        'dataDenascimento' => $estudante->dataofbirth,
        'numeroDocumento' => $estudante->numeroDoDocumento,
        'contact' => $estudante->numeroDotelefone,
        'genero' => Customised::Genero($estudante->genero_id),
        'nacionalidade' =>Customised::Paises($estudante->pais),
        'processo'=>$estudante->reg_Numero
        ];


 

        $encarregados = [
        'nomeCompleto' => $nomeCompleto,
        'contact' => $numeroDotelefone,
        'email' => $email,
        'nacionalidade' => 'Angolano',
        ];
 
            DB::commit();
            return response()->json([
            'Dados'=>'Dados alterado com successo',
             'dadosPessoais'=>$dadosPessoais,
             'dadosCademico'=> $dadosCademico,
             'encarregados'=>$encarregados,
            ], 200);




   } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }




/// DEPOIS DA ALTERACAO O ESTUDANTE REDICIONADO PARA AREIA DE PAGAMENTO



}











}
