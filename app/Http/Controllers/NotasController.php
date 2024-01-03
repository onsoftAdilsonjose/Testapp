<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Meses;
use App\Models\EstudanteSaldo;
use App\Models\Disciplina;
use Throwable;
use App\Models\Payment;
use App\Models\Notas;
use App\Models\Transactions;
use App\Models\AnoLectivo;
use App\Models\Mensalidade;
use App\MyCustomFuctions\Months;

class NotasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->select('users.id as studentID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo')
            ->where('users.usertype','=','Estudante')
            ->get();

        if (!$Estudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Estudante não encontrado.'], 404);
        }

        return response()->json(['TodosEstudantes' => $Estudante_x_Ano_x_Classe]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function VerNotas($classeId, $anolectivoID, $studentID)
    {
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $studentID])
            ->select('users.id as studentID', 'salas.id as salaID', 'turmas.id as turmaID', 'periodos.id as periodoID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo', 'classes.ClassComExam')
            // ->where()
            ->first();
$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([
        'livrode_notas.classeID' => $classeId,
        'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $studentID,
        // 'users.usertype','=','Estudante' 
    ])
    ->select(
        'disciplinas.nomeDisciplina',
        'livrode_notas.Mac1',
        'livrode_notas.Npt1',
        'livrode_notas.Npp1',
        'livrode_notas.Mac2',
        'livrode_notas.Npt2',
        'livrode_notas.Npp2',
        'livrode_notas.Mac3',
        'livrode_notas.Npt3',
        'livrode_notas.Npp3',
        'livrode_notas.disciplinaID'
    )
    ->selectRaw('(livrode_notas.Mac1 + livrode_notas.Npt1 + livrode_notas.Npp1) / 3 AS MediaPrimeiroTrimestre')
    ->selectRaw('(livrode_notas.Mac2 + livrode_notas.Npt2 + livrode_notas.Npp2) / 3 AS MediaSegundoTrimestre')
    ->selectRaw('(livrode_notas.Mac3 + livrode_notas.Npt3 + livrode_notas.Npp3) / 3 AS MediaTerceriroTrimestre');


if ($Estudante_x_Ano_x_Classe->ClassComExam != 0) {
    $Disciplina = $Disciplina->addSelect('livrode_notas.Exam');
}
    $Disciplina = $Disciplina->get();

        foreach ($Disciplina as $disciplina) {
        $disciplina->MediaPrimeiroTrimestre = number_format($disciplina->MediaPrimeiroTrimestre, 2);
        $disciplina->MediaSegundoTrimestre = number_format($disciplina->MediaSegundoTrimestre, 2);
        $disciplina->MediaTerceriroTrimestre = number_format($disciplina->MediaTerceriroTrimestre, 2);
    }


      
        if (!$Estudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Estudante não encontrado.'], 404);
        }


       $TrimestreCount =  Months::Trimestre($anolectivoID);

          return response()->json([
            'TodosEstudantes' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            '$TrimestreCount'=>$TrimestreCount
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function storeNotas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studentID' => 'required',
            'classeID' => 'required',
            'anolectivoID' => 'required',
            'prova' => 'required',
            // 'salaID' => 'required',
            // 'turmaID' => 'required',
            // 'periodoID' => 'required',
            // 'cursoID' => 'required',
            'nota' => ['required', 'numeric', 'min:0', 'max:20'],

            'disciplinaID' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['errors' => $errors], 422);
        }

  if (!is_null($request->prova)) {
        $CustomNota = $request->prova;

        $inserirNotas = Notas::updateOrCreate(
            [
                'studentID' => $request->studentID,
                'classeID' => $request->classeID,
                'anolectivoID' => $request->anolectivoID,
                'disciplinaID' => $request->disciplinaID,
            ],
            [$CustomNota => $request->nota]
             // Use the correct model class name "Notas"
        );
        
       
    }

         

   
$Notas = DB::table('livrode_notas')
->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
->where('studentID', '=', $request->studentID)
->where('anolectivoID', '=', $request->anolectivoID)
->where('classeID', '=', $request->classeID)
->where('disciplinaID', '=', $request->disciplinaID)
->select('livrode_notas.*','nomeDisciplina')
->first();




        return response()->json([
            'Notas' =>  $Notas,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic here
    }





    /**
     * Update the specified resource in storage.
     */
    public function VerNotasEstudante($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID)
    {
        // Update logic here

        $VerNotasEstudante= Estudante_x_Ano_x_Classe::join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
       ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
        ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
        ->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
        ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
        ->where([

        'estudante_x_ano_x_classe.Periodo_id' => $periodoID,
        'estudante_x_ano_x_classe.Turma_id' => $turmaID,
        // 'estudante_x_ano_x_classe.Sala_id'=> $salaID,
        'estudante_x_ano_x_classe.Curso_id' => $cursoID,
        'estudante_x_ano_x_classe.Anolectivo_id' => $anolectivoID,
        'estudante_x_ano_x_classe.Classe_id' => $classeID
        ])
->select(
    'classes.classe_name',
    'curso.nomeCurso',
    // 'salas.nomeSala',
    'turmas.nomeTurma',
    'periodos.nomePeriodo',
    'ano_lectivos.ano_lectivo',
    'curso.id as cursoID',
    'turmas.id as turmaID',
    'periodos.id as asperiodoID',
    'classes.id as classeID',
    'ano_lectivos.id as ano_lectivoID',
    DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
    'users.reg_Numero',
    'users.id as studentID'
)->get();



            if (!$VerNotasEstudante) {
            return response()->json(['error' => 'Estudantes  não encontrado.'], 404);
            }

        return response()->json(['VerNotasEstudante'=>$VerNotasEstudante], 201);







    }





 




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Deletion logic here
    }






    public function BoletimDeNotas(Request $request,$id)
    {
        // este controller vai validar todas as requisoes 
        //concenentes ha boletim de notas e  declaracoes mini pauta da turma  e pauata final
    }



   
    public function NotasParaPauta($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID)
    {

    $Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([
    'livrode_notas.classeID' => $classeID,
    'livrode_notas.anolectivoID' => $anolectivoID,
    ])
    ->select(
    DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
    'users.reg_Numero',
    'users.id as id',
    'disciplinas.nomeDisciplina',
    // 'classes.ClassComExam',
    // 'livrode_notas.Mac1',
    // 'livrode_notas.Npt1',
    // 'livrode_notas.Npp1',
    // 'livrode_notas.Mac2',
    // 'livrode_notas.Npt2',
    // 'livrode_notas.Npp2',
    // 'livrode_notas.Mac3',
    // 'livrode_notas.Npt3',
    // 'livrode_notas.Npp3',
    'livrode_notas.disciplinaID',
    )
    ->selectRaw('(livrode_notas.Mac1 + livrode_notas.Npt1 + livrode_notas.Npp1) / 3 AS MediaPrimeiroTrimestre')
    ->selectRaw('(livrode_notas.Mac2 + livrode_notas.Npt2 + livrode_notas.Npp2) / 3 AS MediaSegundoTrimestre')
    ->selectRaw('(livrode_notas.Mac3 + livrode_notas.Npt3 + livrode_notas.Npp3) / 3 AS MediaTerceriroTrimestre')
    // ->selectRaw('( MediaPrimeiroTrimestre + MediaSegundoTrimestre + MediaTerceriroTrimestre) / 3 AS mediaFinal')

    //->groupBy('id','full_name')
     ;

 // if ($Disciplina->ClassComExam != 0) {
//     // $Disciplina = $Disciplina->addSelect('livrode_notas.Exam');
// }
    $Disciplina = $Disciplina->get();
    foreach ($Disciplina as $disciplina) {
    $disciplina->MediaPrimeiroTrimestre = number_format($disciplina->MediaPrimeiroTrimestre, 2);
    $disciplina->MediaSegundoTrimestre = number_format($disciplina->MediaSegundoTrimestre, 2);
    $disciplina->MediaTerceriroTrimestre = number_format($disciplina->MediaTerceriroTrimestre, 2);
    // $disciplina->mediaFinal = number_format($disciplina->mediaFinal, 2);
    }


      




  $Disciplina= $Disciplina->groupBy("name");





 


      // $TrimestreCount =  Months::Trimestre($anolectivoID);

          return response()->json([
            'Disciplina' =>$Disciplina,
        ]);
    }


}
