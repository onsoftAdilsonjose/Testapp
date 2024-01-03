<?php

namespace App\Http\Controllers\Lista\Turma;


use App\Http\Controllers\Controller;
use App\Models\Estudante_x_Ano_x_Classe;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class EstudanteTurmaController extends Controller
{
    //



 

        public function classesdelista($periodoId, $anolectivoId)
    {


$classesparalistaestudante = DB::table('mensalidade')
->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
->where(['mensalidade.Periodo_id'=> $periodoId, 'mensalidade.Anolectivo_id'=>$anolectivoId])
->select(
        'nomeCurso',
        'nomePeriodo',
        'nomeTurma',
        'nomeSala',
        'classe_name',
        'ano_lectivo',
        'mensalidade.Periodo_id',
        'mensalidade.Turma_id',
        'mensalidade.Curso_id',
        'mensalidade.Anolectivo_id',
        'mensalidade.Classe_id',
        'mensalidade.Periodo_id',
        'mensalidade.Sala_id',

    )
->get();


        return response()->json(['classeslista' => $classesparalistaestudante]);
    }

 

 
        public function listadeclasseestudante($Anolectivo_id,$Periodo_id,$Turma_id,$Sala_id,$Curso_id,$Classe_id)
    {



$Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->Leftjoin('pessoa', 'users.pessoa_id', '=', 'pessoa.id') // commented out as it's not being used
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
    ->where('users.status', 0) // Use single brackets here
    ->select(
        'users.id as id',
        DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"),
        'users.reg_Numero',
        'dataofbirth',
        'numeroDotelefone',
        DB::raw('CASE WHEN genero_id = 1 THEN "male" ELSE "female" END AS gender')
    )
    ->where([
        'estudante_x_ano_x_classe.Periodo_id' => $Periodo_id,
        'estudante_x_ano_x_classe.Anolectivo_id' => $Anolectivo_id,
        'estudante_x_ano_x_classe.Turma_id' => $Turma_id,
        'estudante_x_ano_x_classe.Sala_id' => $Sala_id,
        'estudante_x_ano_x_classe.Curso_id' => $Curso_id,
        'estudante_x_ano_x_classe.Classe_id' => $Classe_id,
    ])
    ->get();


 

             $dadosTurma= Estudante_x_Ano_x_Classe::join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->select(
                'classes.classe_name',
                'salas.nomeSala',
                'turmas.nomeTurma',
                'periodos.nomePeriodo',
                'ano_lectivos.ano_lectivo',
                'curso.nomeCurso')
            ->where([
                    'estudante_x_ano_x_classe.Periodo_id'=> $Periodo_id, 
                    'estudante_x_ano_x_classe.Anolectivo_id'=>$Anolectivo_id,
                    'estudante_x_ano_x_classe.Turma_id'=>$Turma_id,
                    'estudante_x_ano_x_classe.Sala_id'=>$Sala_id,
                    'estudante_x_ano_x_classe.Curso_id'=>$Curso_id,
                    'estudante_x_ano_x_classe.Classe_id'=>$Classe_id,
            ])
            ->first();
          










        return response()->json(['estudantelista' =>$Estudante_x_Ano_x_Classe,
                                  'dadosTurma'=>$dadosTurma
                              ]);
    }

}

