<?php

namespace App\Http\Controllers\UPDATEINFO\NAOMTRICULADO;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstudanteEncarregadoRequest;
use App\Jobs\SendEmailMatriculaJob;
use App\Jobs\SendSmsMatriculaJob;
use App\Models\Pessoa;
use App\Models\Role;
use App\Models\Estudante_x_Ano_x_Classe;
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
use App\Estudante\EstudanteInfounico;
use App\Models\AnoLectivo;
use App\Models\Notas;
class matriculrnaomatriculadocontroller extends Controller
{
    //



 

 public function EstudanteNaoMatriculadoMatricular(Request $request,$reg_Numero){

    $validator = Validator::make($request->all(), [
        'reg_Numero' => 'required|exists:users,reg_Numero',
        'periodoID' => 'required|integer|exists:periodos,id',
        'turmaID' => 'required|integer|exists:turmas,id',
        'salaID' => 'required|integer|exists:salas,id',
        'classeID' => 'required|integer|exists:classes,id',
        'cursoID' => 'required|integer|exists:curso,id',
    ]);

    if ($validator->fails()) {
        $firstError = $validator->errors()->first();
        return response()->json(['error' => $firstError], 422);
    }



    DB::beginTransaction();
    try {

        $estudante = EstudanteInfounico::RegNumber($request->reg_Numero);
        $anoLectivo = AnoLectivo::first();
        $EstudanteInfounico = EstudanteInfounico::EsteAlunojamatriculado($estudante->id,$anoLectivo->id);
        if ($EstudanteInfounico !==null) {

            return $EstudanteInfounico ;
        }


        $EncarregadoInfo= EstudanteInfounico::EncarregadoInfo($estudante->encarregadoID);
        $encarregados =[];
        MatricularEstudante::MatricularOrconfirmar($estudante->id,$request->periodoID,$request->classeID,$request->cursoID,$request->salaID,$request->turmaID);
        $getTodasDisciplinas = MatricularEstudante::getTodasDisciplinas($anoLectivo->id,$request->classeID,$request->periodoID,$request->turmaID,$request->salaID,$request->cursoID);


            foreach ($getTodasDisciplinas as $disciplina) {
            $disciplinaparaaluno = new Notas();
            $disciplinaparaaluno->disciplinaID = $disciplina->Disciplinas_id;
            $disciplinaparaaluno->classeID = $disciplina->Classe_id;
            $disciplinaparaaluno->studentID = $estudante->id;
            $disciplinaparaaluno->anolectivoID = $disciplina->Anolectivo_id;
            $disciplinaparaaluno->salaID = $disciplina->Sala_id;
            $disciplinaparaaluno->turmaID = $disciplina->Turma_id;
            $disciplinaparaaluno->periodoID = $disciplina->Periodo_id;
            $disciplinaparaaluno->CursoID = $disciplina->Curso_id;
            $disciplinaparaaluno->save();
            }



        //MatricularEstudante::DisciplinaParaAluno($getTodasDisciplinas,$estudante->id);
        $mensalidadeId = Pagamento::SingleStudentDetalhes($request->classeID,$anoLectivo->id,$estudante->id);
        $dadosCademico = MatricularEstudante::MatriculadeAluno($mensalidadeId);
        $dadosCademico->estudanteid = $estudante->id;

        $dadosPessoais = [
        'nomeCompleto' =>$estudante->nomeCompleto,
        'dataDenascimento' => $estudante->dataofbirth,
        'numeroDocumento' =>$estudante->numeroDoDocumento,
        'contact' => $estudante->numeroDotelefone,
        'genero' =>Customised::Genero($estudante->genero_id),
        'nacionalidade' =>Customised::Paises($estudante->pais),
        'processo'=>$estudante->reg_Numero
        ];


 
        if ($EncarregadoInfo !== null) {
            // Your code here
            $encarregados = [
                'nomeCompleto' => $EncarregadoInfo->nomeCompleto,
                'contact' => $EncarregadoInfo->numeroDotelefone,
                'email' => $EncarregadoInfo->email,
                'nacionalidade' => 'Angolano',
            ];
        }

 

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






}
  