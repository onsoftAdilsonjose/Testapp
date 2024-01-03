<?php

namespace App\Http\Controllers\Users\Professor;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\DisciplinaParaClasse;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Notas;
use App\Models\Faltas;
use App\Professor\Professorfunctions;
use App\Models\ProcessoDisciplinar;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProfessorController extends Controller
{
    //






/**
 * @OA\Get (
 *     path="/api/Professor/peresrofvtudante/anolectivoID/{anolectivoID}/turmaID/{turmaID}/periodoID/{periodoID}/cursoID/{cursoID}/classeID/{classeID}",
 *     tags={"Professor"},
 *     security={{"bearerAuth":{}}},
 *     summary="Visualizar notas do estudante",
 *     description="Endpoint para visualizar estudantes de uma determinada turma",
 *     @OA\Parameter(
 *         name="anolectivoID",
 *         in="query",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="turmaID",
 *         in="query",
 *         required=true,
 *         description="ID da turma",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="periodoID",
 *         in="query",
 *         required=true,
 *         description="ID do período",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="cursoID",
 *         in="query",
 *         required=true,
 *         description="ID do curso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="classeID",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="estudantes de uma turma",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="classe_name", type="string", example="Classe A"),
 *                 @OA\Property(property="nomeCurso", type="string", example="Ciências Físicas e Biológicas"),
 *                 @OA\Property(property="nomeTurma", type="string", example="Turma 1"),
 *                 @OA\Property(property="nomePeriodo", type="string", example="Período 1"),
 *                 @OA\Property(property="ano_lectivo", type="string", example="2023"),
 *                 @OA\Property(property="cursoID", type="integer", example=1),
 *                 @OA\Property(property="turmaID", type="integer", example=1),
 *                 @OA\Property(property="periodoID", type="integer", example=1),
 *                 @OA\Property(property="classeID", type="integer", example=1),
 *                 @OA\Property(property="ano_lectivoID", type="integer", example=1),
 *                 @OA\Property(property="full_name", type="string", example="John Doe"),
 *                 @OA\Property(property="reg_Numero", type="string", example="123456"),
 *                 @OA\Property(property="studentID", type="integer", example=1),
 *             )
 *         )
 *     )
 * )
 */


public function profVerNotasEstudante($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID){


        $VerNotasEstudante= Estudante_x_Ano_x_Classe::join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
        ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
        ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
        ->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
        ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
        ->where([
         'estudante_x_ano_x_classe.Periodo_id' => $periodoID,
        'estudante_x_ano_x_classe.Turma_id' => $turmaID,
        // // 'estudante_x_ano_x_classe.Sala_id'=> $salaID,
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






 return response()->json(['VerNotasEstudante'=>$VerNotasEstudante], 201);







}



  /**
 * @OA\Get(
 *     path="/api/Professor/VerNotas/Classe/{classeId}/Anolectivo/{anolectivoID}/Estudante/{studentID}",
 *     tags={"Professor"},
 *     security={{"bearerAuth":{}}},
 *     summary="Visualizar notas do estudante em uma disciplina",
 *     description="Endpoint para visualizar as notas do estudante em uma disciplina específica com base nos parâmetros fornecidos",
 *     @OA\Parameter(
 *         name="classeId",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="anolectivoID",
 *         in="query",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="studentID",
 *         in="query",
 *         required=true,
 *         description="ID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Notas do estudante em uma disciplina",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="TodosEstudantes", type="object",
 *                 @OA\Property(property="classe_name", type="string", example="Classe A"),
 *                 @OA\Property(property="nomeCurso", type="string", example="Ciências Físicas e Biológicas"),
 *                 @OA\Property(property="nomeTurma", type="string", example="Turma 1"),
 *                 @OA\Property(property="nomePeriodo", type="string", example="Período 1"),
 *                 @OA\Property(property="ano_lectivo", type="string", example="2023"),
 *                 @OA\Property(property="cursoID", type="integer", example=1),
 *                 @OA\Property(property="turmaID", type="integer", example=1),
 *                 @OA\Property(property="periodoID", type="integer", example=1),
 *                 @OA\Property(property="classeID", type="integer", example=1),
 *                 @OA\Property(property="ano_lectivoID", type="integer", example=1),
 *                 @OA\Property(property="full_name", type="string", example="John Doe"),
 *                 @OA\Property(property="reg_Numero", type="string", example="123456"),
 *                 @OA\Property(property="studentID", type="integer", example=1),
 *                 @OA\Property(property="ClassComExam", type="integer", example=1),
 *             ),
 *             @OA\Property(property="Disciplina", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="nomeDisciplina", type="string", example="Matemática"),
 *                     @OA\Property(property="MediaPrimeiroTrimestre", type="string", example="8.50"),
 *                     @OA\Property(property="MediaSegundoTrimestre", type="string", example="7.75"),
 *                     @OA\Property(property="MediaTerceriroTrimestre", type="string", example="9.00"),
 *                     @OA\Property(property="disciplinaID", type="integer", example=1),
 *                     @OA\Property(property="Exam", type="integer", example=90),
 *                 )
 *             )
 *         )
 *     )
 * )
 */
 

      public function proferNotass($classeId, $anolectivoID, $studentID)
    {

        $professorId = Auth::id();
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
    ->join('disciplinaparaclasse', 'disciplinaparaclasse.id', '=', 'livrode_notas.disciplinaID')

    ->where([
        'livrode_notas.classeID' => $classeId,
        'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $studentID,
        'disciplinaparaclasse.Professor_id'=>$professorId,
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


       //$TrimestreCount =  Months::Trimestre($anolectivoID);

          return response()->json([
            'TodosEstudantes' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            //'$TrimestreCount'=>$TrimestreCount
        ]);
    }


 


/**
 * @OA\Post(
 *     path="/api/Professor/professorstoreNotas",
 *     tags={"Professor"},
 *     security={{"bearerAuth":{}}},
 *     summary="Armazenar notas do estudante",
 *     description="Endpoint para armazenar as notas do estudante com base nos parâmetros fornecidos",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"studentID", "classeID", "anolectivoID", "prova", "nota", "disciplinaID"},
 *             @OA\Property(property="studentID", type="integer", example=1),
 *             @OA\Property(property="classeID", type="integer", example=1),
 *             @OA\Property(property="anolectivoID", type="integer", example=2023),
 *             @OA\Property(property="prova", type="string", example="Npt1"),
 *             @OA\Property(property="nota", type="numeric", example=15),
 *             @OA\Property(property="disciplinaID", type="integer", example=1),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Notas do estudante armazenadas com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="Notas", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="studentID", type="integer", example=1),
 *                 @OA\Property(property="classeID", type="integer", example=1),
 *                 @OA\Property(property="anolectivoID", type="integer", example=2023),
 *                 @OA\Property(property="disciplinaID", type="integer", example=1),
 *                 @OA\Property(property="Npt1", type="numeric", example=15),
 *                 @OA\Property(property="created_at", type="string", example="2023-01-01 12:00:00"),
 *                 @OA\Property(property="updated_at", type="string", example="2023-01-01 12:00:00"),
 *                 @OA\Property(property="nomeDisciplina", type="string", example="Matemática"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="errors", type="object"),
 *         )
 *     )
 * )
 */
 
    public function ProfessorstoreNotas(Request $request)
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






  public function proffverDisciplinas($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID){

     $professorId = Auth::id();

$Disciplinas = DB::table('disciplinas')
->join('disciplinaparaclasse', 'disciplinaparaclasse.Disciplina_id', '=', 'disciplinas.id')
->where([
'disciplinaparaclasse.Anolectivo_id'=>$anolectivoID ,
'disciplinaparaclasse.Curso_id'=>$cursoID ,
'disciplinaparaclasse.Turma_id'=>$turmaID,
'disciplinaparaclasse.Periodo_id'=>$periodoID,
'disciplinaparaclasse.Classe_id'=>$classeID,
'disciplinaparaclasse.Professor_id'=>$professorId,
])->select('nomeDisciplina','disciplinas.id as id')
->get();


        return response()->json([
            'disciplinas'=>$Disciplinas 
        ], 201);

    }



    public function proffverminipauta(){




    }
    public function proffverpautatrimestral(){




    }

    public function proffverpautaanual(){




    }



    public function proffavaliacoescontinuas(){




    }


    public function proffprovacomplementares(){




    }


 


 /**
 * @OA\Post(
 *     path="/api/Professor/storeFaltas",
 *     tags={"Professor"},
 *     summary="Armazenar informações de falta do estudante",
 *     description="Endpoint para armazenar as informações de falta do estudante com base nos parâmetros fornecidos",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"idattendance_types", "studentID", "classeID", "anolectivoID", "attendance_date", "disciplinaID"},
 *             @OA\Property(property="idattendance_types", type="integer", example=1),
 *             @OA\Property(property="studentID", type="integer", example=1),
 *             @OA\Property(property="classeID", type="integer", example=1),
 *             @OA\Property(property="anolectivoID", type="integer", example=2023),
 *             @OA\Property(property="attendance_date", type="string", format="date", example="2023-01-01"),
 *             @OA\Property(property="disciplinaID", type="integer", example=1),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Dados de falta do estudante armazenados com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="messagem", type="string", example="Dados armazenados com sucesso"),
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
 

public function storeFaltas(Request $request)
{
    $validator = Validator::make($request->all(), [
        // 'idattendance_types' => 'required|integer',
        // 'studentID' => 'required|integer',
        // 'anolectivoID' => 'required|integer',
        // 'classeID' => 'required|integer',
        // 'attendance_date' => 'required|date',
        // 'disciplinaID' => 'required|integer',
        // 'attendance_date' => 'required|date',
    ]);

    if ($validator->fails()) {
        $firstError = $validator->errors()->first();
        return response()->json(['error' => $firstError], 422);
    }

    DB::beginTransaction();
    try {
        $existingAttendance = Faltas::where('attendance_date', $request->attendance_date)
            ->where('studentID', $request->studentID)
            ->where('disciplinaID', $request->disciplinaID)
            ->first();

        if ($existingAttendance) {
            // Update the existing attendance record
            $existingAttendance->idattendance_types = $request->idattendance_types;
            // Update other fields as needed
            $existingAttendance->save();
        } else {
            // Create a new attendance record
            $newAttendance = new Faltas();
            $newAttendance->idattendance_types = $request->idattendance_types;
            // Set other fields
            $newAttendance->attendance_date = $request->attendance_date;
            $newAttendance->studentID = $request->studentID;
            $newAttendance->classeID = $request->classeID;
            $newAttendance->anolectivoID = $request->anoLectivoID;
            $newAttendance->disciplinaID = $request->disciplinaID;
            // Set other fields as needed
            $newAttendance->save();
          




        }

        DB::commit();

        return response()->json([
            'messagem' => 'Dados armazenados com sucesso',
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




   public function processodisciplinar(Request $request){


$validatedData = $request->validate([
            'motivo' => 'required|string',
            'student_id' => 'required|integer',
            'registradopor' => 'required|string',
            'testemunha' => 'string|nullable',
            'data' => 'required|date',
            'Classe_id' => 'required|integer',
            'Anolectivo_id' => 'required|integer',
            // Add validation rules for other fields here
        ]);

        // Create a new ProcessoDisciplinar instance and fill it with validated data
        $processoDisciplinar = ProcessoDisciplinar::create($validatedData);




        return response()->json([
        'processoDisciplinar'=> $processoDisciplinar,
        ], 201);
    }


/**
 * @OA\Get(
 *     path="/api/Professor/alunoatraso/disciplinas/{disciplinaid}/anolectivo/{ano}/classe/{classid}",
 *     tags={"Professor"},
 *     summary="Consultar disciplinas pendentes dos estudantes",
 *     description="Endpoint para consultar as disciplinas pendentes dos estudantes com base nos parâmetros fornecidos",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="disciplinaid",
 *         in="query",
 *         required=true,
 *         description="ID da disciplina",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="anolectivo",
 *         in="query",
 *         required=true,
 *         description="Ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="classeID",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Disciplinas pendentes consultadas com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="disciplinas", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="reg_Numero", type="integer"),
 *                     @OA\Property(property="nomeDisciplina", type="string"),
 *                     @OA\Property(property="Mac1", type="numeric"),
 *                     @OA\Property(property="Npt1", type="numeric"),
 *                     @OA\Property(property="Npp1", type="numeric"),
 *                     @OA\Property(property="Mac2", type="numeric"),
 *                     @OA\Property(property="Npt2", type="numeric"),
 *                     @OA\Property(property="Npp2", type="numeric"),
 *                     @OA\Property(property="Mac3", type="numeric"),
 *                     @OA\Property(property="Npt3", type="numeric"),
 *                     @OA\Property(property="Npp3", type="numeric"),
 *                     @OA\Property(property="disciplinaID", type="integer"),
 *                     @OA\Property(property="MediaPrimeiroTrimestre", type="numeric"),
 *                     @OA\Property(property="MediaSegundoTrimestre", type="numeric"),
 *                     @OA\Property(property="MediaTerceriroTrimestre", type="numeric"),
 *                     @OA\Property(property="Exam", type="numeric", nullable=true),
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


 public function professorDisciplinaspendentes($disciplinaid,$anolectivo,$classeID){


$professorId = Auth::id();
$Estudante_x_Ano_x_Classe = DB::table('classes')->where(['id'=>$classeID])->select('ClassComExam')->first();

$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
->join('users', 'users.id', '=', 'livrode_notas.studentID')
->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
->join('disciplinaparaclasse', 'disciplinaparaclasse.Disciplina_id', '=', 'livrode_notas.disciplinaID')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'livrode_notas.studentID')

    ->where([
        'livrode_notas.classeID' => $classeID,
        'livrode_notas.anolectivoID' => $anolectivo,
       // 'livrode_notas.studentID' => $studentID,
        'disciplinaparaclasse.Professor_id'=>$professorId,
         //'estudante_x_ano_x_classe.Anolectivo_id'=>$anolectivoID,
         'livrode_notas.disciplinaID'=>$disciplinaid,

    ])
    ->select(
         DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
        'users.reg_Numero',
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


        return response()->json([
            'disciplinas'=>$Disciplina 
        ], 201);

    }





/**
 * @OA\Get(
 *     path="/api/Professor/profconsultarfaltas/{anolectivoID}/turna/{turmaID}/periodo/{periodoID}/curso/{cursoID}/classe/{classeID}/disciplina/{disciplinaid}",
 *     tags={"Professor"},
 *     summary="Consultar faltas dos estudantes",
 *     description="Endpoint para consultar as faltas dos estudantes com base nos parâmetros fornecidos",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="anolectivoID",
 *         in="query",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="turmaID",
 *         in="query",
 *         required=true,
 *         description="ID da turma",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="periodoID",
 *         in="query",
 *         required=true,
 *         description="ID do período",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="cursoID",
 *         in="query",
 *         required=true,
 *         description="ID do curso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="classeID",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="disciplinaid",
 *         in="query",
 *         required=true,
 *         description="ID da disciplina",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Faltas dos estudantes consultadas com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="estudantefaltas", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="reg_Numero", type="integer"),
 *                     @OA\Property(property="disciplina_id", type="integer"),
 *                     @OA\Property(property="attendance_Nome", type="string"),
 *                     @OA\Property(property="attendance_date", type="string"),
 *                     @OA\Property(property="nomeDisciplina", type="string"),
 *                     @OA\Property(property="classe_name", type="string"),
 *                     @OA\Property(property="ano_lectivo", type="integer"),
 *                     @OA\Property(property="presente_count", type="integer"),
 *                     @OA\Property(property="ausente_count", type="integer"),
 *                     @OA\Property(property="atrasado_count", type="integer"),
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


public function professoreconsultarfaltas($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID,$disciplinaid){
      $professorId = Auth::id();
   

    $estudantefaltas = DB::table('attendance')
        ->join('users', 'users.id', '=', 'attendance.studentID')
        ->join('classes', 'classes.id', '=', 'attendance.classeID')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'attendance.anolectivoID')
        ->join('attendance_types', 'attendance_types.id', '=', 'attendance.idattendance_types')
        ->join('disciplinas', 'disciplinas.id', '=', 'attendance.disciplinaID')
        ->join('disciplinaparaclasse', 'disciplinaparaclasse.Disciplina_id', '=', 'disciplinas.id')
        ->select(
        DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
            'users.reg_Numero',
            'disciplinas.id as disciplina_id',
            'attendance_Nome',
            'attendance_date',
            'nomeDisciplina',
            'classe_name',
            'ano_lectivo',
            DB::raw("SUM(CASE WHEN attendance_types.id = 1 THEN 1 ELSE 0 END) AS presente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 2 THEN 1 ELSE 0 END) AS ausente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 3 THEN 1 ELSE 0 END) AS atrasado_count")
        )
        ->where([
           'disciplinas.id' => $disciplinaid, 
            'anolectivoID' => $anolectivoID,
            'classes.id' =>$classeID,
            'disciplinaparaclasse.Periodo_id' =>$periodoID,
            'disciplinaparaclasse.Classe_id' =>$classeID,
            'disciplinaparaclasse.Anolectivo_id' =>$anolectivoID,
             'disciplinaparaclasse.Professor_id' =>$professorId,
        ])
        ->groupBy('disciplinas.id', 'attendance_Nome','attendance_date', 'nomeDisciplina', 'classe_name', 'ano_lectivo','full_name','reg_Numero')
        ->get();

    return response()->json([
        'estudantefaltas' => $estudantefaltas,
    ], 200);
}














/**
 * @OA\Get(
 *     path="/api/Professor/consultarnotas/anolectivo/{anolectivoID}/turna/{turmaID}/periodo/{periodoID}/curso/{cursoID}/classe/{classeID}/disciplina/{disciplinaid}",
 *     tags={"Professor"},
 *     summary="Consultar notas dos estudantes",
 *     description="Endpoint para consultar as notas dos estudantes com base nos parâmetros fornecidos",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="anolectivoID",
 *         in="query",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="turmaID",
 *         in="query",
 *         required=true,
 *         description="ID da turma",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="periodoID",
 *         in="query",
 *         required=true,
 *         description="ID do período",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="cursoID",
 *         in="query",
 *         required=true,
 *         description="ID do curso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="classeID",
 *         in="query",
 *         required=true,
 *         description="ID da classe",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="disciplinaid",
 *         in="query",
 *         required=true,
 *         description="ID da disciplina",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Notas dos estudantes consultadas com sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="disciplinas", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="reg_Numero", type="integer"),
 *                     @OA\Property(property="nomeDisciplina", type="string"),
 *                     @OA\Property(property="Mac1", type="numeric"),
 *                     @OA\Property(property="Npt1", type="numeric"),
 *                     @OA\Property(property="Npp1", type="numeric"),
 *                     @OA\Property(property="Mac2", type="numeric"),
 *                     @OA\Property(property="Npt2", type="numeric"),
 *                     @OA\Property(property="Npp2", type="numeric"),
 *                     @OA\Property(property="Mac3", type="numeric"),
 *                     @OA\Property(property="Npt3", type="numeric"),
 *                     @OA\Property(property="Npp3", type="numeric"),
 *                     @OA\Property(property="disciplinaID", type="integer"),
 *                     @OA\Property(property="MediaPrimeiroTrimestre", type="numeric"),
 *                     @OA\Property(property="MediaSegundoTrimestre", type="numeric"),
 *                     @OA\Property(property="MediaTerceriroTrimestre", type="numeric"),
 *                     @OA\Property(property="Exam", type="numeric", nullable=true),
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


  public function proffconsultarnotas($anolectivoID,$turmaID,$periodoID ,$cursoID,$classeID,$disciplinaid){

     $professorId = Auth::id();
    $Estudante_x_Ano_x_Classe = DB::table('classes')->where(['id'=>$classeID])->select('ClassComExam')->first();
$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
->join('users', 'users.id', '=', 'livrode_notas.studentID')
->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
->join('disciplinaparaclasse', 'disciplinaparaclasse.id', '=', 'livrode_notas.disciplinaID')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'livrode_notas.studentID')
    ->where([
            'livrode_notas.classeID' => $classeID,
            'livrode_notas.anolectivoID' => $anolectivoID,
            // 'livrode_notas.studentID' => $studentID,
            'disciplinaparaclasse.Professor_id'=>$professorId,
            //'estudante_x_ano_x_classe.Anolectivo_id'=>$anolectivoID,
            'livrode_notas.disciplinaID'=>$disciplinaid,
    ])
    ->select(
         DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
        'users.reg_Numero',
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
        return response()->json([
            'disciplinas'=>$Disciplina 
        ], 201);

    }








}
