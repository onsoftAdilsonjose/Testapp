<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;
use App\Models\Disciplina;
use App\Models\User;
use App\Models\DisciplinaParaClasse;
use Illuminate\Support\Facades\Validator;


class DisciplinaParaClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
$disciplinaParaClasse = DisciplinaParaClasse::Join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
    ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
    ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
    ->join('disciplinas','disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
    ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
    ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
    ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
    ->select(
        'disciplinaparaclasse.id',
        'disciplinas.nomeDisciplina',
        'nomeCurso',
        'nomePeriodo',
        'nomeTurma',
        'nomeSala',
        'classe_name',
        'ano_lectivo',
        DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS nomeCopleto"),
        'users.id as profId'
    )
    ->get();

  return response()->json(['DisciplinaParaClasse' => $disciplinaParaClasse]);


    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{





    // Validate the request
$validator = Validator::make($request->all(), [
    'professor_id' => 'required|integer|exists:users,id',
    'disciplinaId' => 'required|integer|exists:disciplinas,id',
    'periodo' => 'required|integer|exists:periodos,id',
    'turma' => 'required|integer|exists:turmas,id',
    'sala_id' => 'required|integer|exists:salas,id',
    'classe' => 'required|integer|exists:classes,id',
    'curso' => 'required|integer|exists:curso,id',
]);

if ($validator->fails()) {
    return response()->json(['error' => $validator->errors()], 422);
}




     $anoLectivo   = DB::table('ano_lectivos')->first();
// Loop through each disciplina and check for existing records
            $existingRecord = DisciplinaParaClasse::where([
            'Disciplina_id'=>$request->disciplinaId,
            'professor_id'=>$request->professor_id,
            'Periodo_id'=>$request->periodo,
            'Turma_id'=>$request->turma,
            'Sala_id'=>$request->sala_id,
            'Classe_id'=>$request->classe,
            'Curso_id'=>$request->curso,
            'Anolectivo_id'=>$anoLectivo->id])      
            ->first();
    if ($existingRecord) {
        return response()->json(['errors' => 'Nao e Possivel Duplicar Dados'], 422);
    }


 





    $anolectivo = DB::table('ano_lectivos')->select('id')->first();



        $disciplinaParaClasse = new DisciplinaParaClasse();
        $disciplinaParaClasse->Professor_id = $request->professor_id;
        $disciplinaParaClasse->Disciplina_id =$request->disciplinaId;
        $disciplinaParaClasse->Periodo_id = $request->periodo;
        $disciplinaParaClasse->Turma_id = $request->turma;
        $disciplinaParaClasse->Sala_id = $request->sala_id;
        $disciplinaParaClasse->Classe_id = $request->classe;
        $disciplinaParaClasse->Curso_id = $request->curso;
        $disciplinaParaClasse->Anolectivo_id = $anolectivo->id;
        $disciplinaParaClasse->save();

        $disciplinaData = DisciplinaParaClasse::Join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
            ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
            ->join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
            ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
            ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
            ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->where('disciplinaparaclasse.id', '=', $disciplinaParaClasse->id)
            ->select(
                'disciplinaparaclasse.id',
                'disciplinas.nomeDisciplina',
                'nomeCurso',
                'nomePeriodo',
                'nomeTurma',
                'nomeSala',
                'classe_name',
                'ano_lectivo',
                DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS nomeCompleto"),
                'users.id as profId'
            )
            ->first(); // Use first() to retrieve a single record

     
    

    return response()->json(['DisciplinaParaClasse' => $disciplinaData]);
}

   

}


