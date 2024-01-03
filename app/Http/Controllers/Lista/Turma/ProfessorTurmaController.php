<?php

namespace App\Http\Controllers\Lista\Turma;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaParaClasse;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ProfessorTurmaController extends Controller
{
    //







 
        public function listadeprofessores($Anolectivo_id,$Periodo_id,$Turma_id,$Sala_id,$Curso_id,$Classe_id)
    {



        $listadeprofessores = DisciplinaParaClasse::join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
            // ->join('pessoa', 'users.pessoa_id', '=', 'pessoa.id') // Fix the join condition here
             ->join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
            ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
            ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
            ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->where(['users.status' => 0])
            ->select(
            DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"),
            'users.reg_Numero',
            'dataofbirth',
            'numeroDotelefone',
             'nomeDisciplina',)
             ->where([
                    'disciplinaparaclasse.Periodo_id'=> $Periodo_id, 
                    'disciplinaparaclasse.Anolectivo_id'=>$Anolectivo_id,
                    'disciplinaparaclasse.Turma_id'=>$Turma_id,
                    'disciplinaparaclasse.Sala_id'=>$Sala_id,
                    'disciplinaparaclasse.Curso_id'=>$Curso_id,
                    'disciplinaparaclasse.Classe_id'=>$Classe_id,
             ])
            ->get();






       $dadosTurma = DisciplinaParaClasse::join('curso', 'curso.id', '=','disciplinaparaclasse.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
            ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
            ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->select(
                'classes.classe_name',
                'curso.nomeCurso',
                'salas.nomeSala',
                'turmas.nomeTurma',
                'periodos.nomePeriodo',
                'ano_lectivos.ano_lectivo',
            )
             ->where([
                    'disciplinaparaclasse.Periodo_id'=> $Periodo_id, 
                    'disciplinaparaclasse.Anolectivo_id'=>$Anolectivo_id,
                    'disciplinaparaclasse.Turma_id'=>$Turma_id,
                    'disciplinaparaclasse.Sala_id'=>$Sala_id,
                    'disciplinaparaclasse.Curso_id'=>$Curso_id,
                    'disciplinaparaclasse.Classe_id'=>$Classe_id,
             ])
            ->first();









        return response()->json(['listadeprofessores' =>$listadeprofessores,
                                 'dadosTurma'=>$dadosTurma]);
    }



}
