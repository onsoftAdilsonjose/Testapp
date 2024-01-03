<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Illuminate\Support\Arr;
use App\Models\CursoDisciplinaClasse;
class CursoDisciplinaClasseController extends Controller
{
    //

    public function index()
    {







$CursoDisciplinaClasse = CursoDisciplinaClasse::join('tipodesciplina', 'tipodesciplina.id', '=', 'cursodisciplinaclasse.tipodeDisciplinaid')
->join('curso', 'curso.id', '=', 'cursodisciplinaclasse.cursoId')
->join('classes', 'classes.id', '=', 'cursodisciplinaclasse.classId')
->join('disciplinas', 'disciplinas.id', '=', 'cursodisciplinaclasse.disciplinaId')
->select('nomeDisciplina','TipoNome','classe_name','nomeCurso')->get();

return response()->json(['CursoDisciplinaClasse'=>$CursoDisciplinaClasse]);
}







public function store(Request $request)
{





    
    $validator = Validator::make($request->all(), [
        'classe' => 'required|integer|exists:classes,id',
        //'curso' => 'required|integer|exists:curso,id',
         'curso' => 'required|integer',
        'disciplinas' => 'required|array',
        'disciplinas.*.disciplinaId' => 'required|integer|exists:disciplinas,id',
    ]);

    if ($validator->fails()) {
        $firstError = $validator->errors()->first();
        return response()->json(['error' => $firstError], 422);
    }








     DB::beginTransaction();
    try {
        $cursos = ($request->classe  <= 10) ? 5 : $request->curso;
        $curso = []; // Initialize an empty array to store the data

        $disciplinasData = $request->disciplinas;

        foreach ($disciplinasData as $disciplinaData) {
        $validarDisciplinaExistente = DB::table('cursodisciplinaclasse')
        ->where([
        'disciplinaId' => $disciplinaData['disciplinaId'],
        'cursoId' => $cursos,
        'classId' => $request->classe,
        ])->exists();
        if ($validarDisciplinaExistente) {
        return response()->json(['error' => 'Disciplinas e Classe Ja Cadastrada'], 422);
        }

        }


//return $request->all();

 
 


        foreach ($disciplinasData as $disciplinaData) {
            $cursoDisciplinaClasse = new CursoDisciplinaClasse();
            $cursoDisciplinaClasse->disciplinaId = $disciplinaData['disciplinaId'];
            $cursoDisciplinaClasse->tipodeDisciplinaid = $disciplinaData['tipo'];
            $cursoDisciplinaClasse->cursoId = $cursos;
            $cursoDisciplinaClasse->nuclear =$disciplinaData['nuclear'];
            $cursoDisciplinaClasse->classId = $request->classe;
            $cursoDisciplinaClasse->save();

            // Append the data to the $curso array
            $curso[] = $cursoDisciplinaClasse;
        }


        DB::commit();
        return response()->json([
            'messagem' => 'Dados armazenados com sucesso', 
            'curso' => $curso], 
            201);
    } catch (\Exception $e) {
         DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}







    public function show($id)
    {
        $item = CursoDisciplinaClasse::findOrFail($id);
        return response()->json($item);
    }
























}
