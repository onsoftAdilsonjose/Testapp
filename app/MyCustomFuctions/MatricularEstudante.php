<?php

namespace App\MyCustomFuctions;

use App\Models\DisciplinaParaClasse;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Meses;
use App\Models\Notas;
use App\Models\Role;
use App\Models\Transactions;
use App\Models\User;
use App\Models\classes;
use Carbon\Carbon;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;
use App\MyCustomFuctions\Pagamento;
use Illuminate\Support\Facades\Log;

class MatricularEstudante
{




    public static function MatricularOrconfirmar($student_id, $Periodo_id, $Classe_id, $Curso_id, $Sala_id, $Turma_id)
    {



        $anolectivo = DB::table('ano_lectivos')->select('id')->first();
        $Estudante_x_Ano_x_Classe = new Estudante_x_Ano_x_Classe();
        $Estudante_x_Ano_x_Classe->student_id = $student_id;
        $Estudante_x_Ano_x_Classe->Periodo_id = $Periodo_id;
        $Estudante_x_Ano_x_Classe->Turma_id = $Turma_id;
        $Estudante_x_Ano_x_Classe->Sala_id = $Sala_id;
        $Estudante_x_Ano_x_Classe->Classe_id = $Classe_id;
        $Estudante_x_Ano_x_Classe->Curso_id = $Curso_id;
        $Estudante_x_Ano_x_Classe->Anolectivo_id = $anolectivo->id;
        $Estudante_x_Ano_x_Classe->save();
    }






    public static function MatriculadeAluno($mensalidadeId)
    {
        $Mensalidade = DB::table('mensalidade')
            ->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
            ->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
            ->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
            ->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
            ->where('mensalidade.id', '=', $mensalidadeId)
            ->select(
                //'mensalidade.Propina_Anual',
                'mensalidade.MatriculaPreco as Preco' ,
                'classe_name',
                'nomeCurso',
                'nomePeriodo',
                'ano_lectivo',
                'nomeSala',
                'nomeTurma',
                'ano_lectivo',
                'curso.id as cursoId',
                'classes.id as classeId',
                'periodos.id as peridoId',
                'turmas.id as turmaID',
                'salas.id as salaID',
                'ano_lectivos.id as anolectivoId'
            )
            ->first();

            $Servico = 'Matricula';
            $Mensalidade->Servico = $Servico;


        return $Mensalidade;
    }





    public static function ConfirmacaodeAluno($mensalidadeId)
    {
        $Mensalidade = DB::table('mensalidade')
            ->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
            ->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
            ->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
            ->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
            ->where('mensalidade.id', '=', $mensalidadeId)
            ->select(
                //'mensalidade.Propina_Anual',

                'mensalidade.ConfirmacaoPreco as Preco',
                'classe_name',
                'nomeCurso',
                'nomePeriodo',
                'ano_lectivo',
                'nomeSala',
                'nomeTurma',
                'ano_lectivo',
                'turmas.id as turmaID',
                'salas.id as salaID',
                'curso.id as cursoId',
                'classes.id as classeId',
                'periodos.id as peridoId',
                'ano_lectivos.id as anolectivoId'
            )
            ->first();

            $Servico = 'Confirmacao';
            $Mensalidade->Servico = $Servico;













        return $Mensalidade;
    }









    public static function getTodasDisciplinas($Anolectivo_id, $Classe_id, $Periodo_id, $Turma_id, $Sala_id, $Curso_id)
    {



        $getTodasDisciplinas = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
        
            ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
            ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
            ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
            ->where('Anolectivo_id', $Anolectivo_id)
            ->where('Classe_id', $Classe_id)
            ->where('Periodo_id', $Periodo_id)
            ->where('Turma_id', $Turma_id)
            ->where('Sala_id', $Sala_id)
            ->where('Curso_id', $Curso_id)
            ->select(
                'turmas.id as Turma_id',
                'periodos.id as Periodo_id',
                'salas.id as Sala_id',
                'ano_lectivos.id as Anolectivo_id',
                'classes.id as Classe_id',
                'curso.id as Curso_id',
                'disciplinas.id as Disciplinas_id'
            )
            ->get();
        return $getTodasDisciplinas;
    }


 

    public static function DisciplinaParaAluno($getTodasDisciplinas, $student_id)
    {  


       
        foreach ($getTodasDisciplinas as $disciplina) {
            $disciplinaparaaluno = new Notas();
            $disciplinaparaaluno->disciplinaID = $disciplina->Disciplinas_id;
            $disciplinaparaaluno->classeID = $disciplina->Classe_id;
            $disciplinaparaaluno->studentID = $student_id;
            $disciplinaparaaluno->anolectivoID = $disciplina->Anolectivo_id;
            $disciplinaparaaluno->salaID = $disciplina->Sala_id;
            $disciplinaparaaluno->turmaID = $disciplina->Turma_id;
            $disciplinaparaaluno->periodoID = $disciplina->Periodo_id;
            $disciplinaparaaluno->CursoID = $disciplina->Curso_id;
            $disciplinaparaaluno->save();
        }
        
    }




  public static function deleteDisciplinaParaAluno($getTodasDisciplinas, $student_id)
{
    foreach ($getTodasDisciplinas as $disciplina) {
        Notas::where('disciplinaID', $disciplina->Disciplinas_id)
            ->where('classeID', $disciplina->Classe_id)
            ->where('studentID', $student_id)
            ->where('anolectivoID', $disciplina->Anolectivo_id)
            ->where('salaID', $disciplina->Sala_id)
            ->where('turmaID', $disciplina->Turma_id)
            ->where('periodoID', $disciplina->Periodo_id)
            ->where('CursoID', $disciplina->Curso_id)
            ->delete();
    }
}












    public static function EstudanteDetalhes_unico($student_id, $anolectivo_id)
    {
        $EstudanteDetalhes_unico = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->Leftjoin('pessoa', 'pessoa.id', '=', 'users.pessoa_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')

        ->select('users.id', DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"), 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo','users.dataofbirth','pessoa.tipoDeDocumento','pessoa.numeroDoDocumento','users.numeroDotelefone')

            ->where(['users.id' => $student_id, 'estudante_x_ano_x_classe.Anolectivo_id' => $anolectivo_id])
            ->first();



        return $EstudanteDetalhes_unico;
    }





        public static function Encarregado_unico($id)
        {



        $Estudante = DB::table('users')
        ->where('id', '=', $id)
        ->select('id','encarregadoID')
        ->first();

//Log::info('Encarregado ID: ' . $Estudante->encarregado);
        $Encarregado_unico =  DB::table('users')
        ->where('id', '=', $Estudante->encarregadoID)
        ->select('users.id', DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"), 'users.reg_Numero', 'users.email','users.numeroDotelefone')
        ->first();

     if (empty($Encarregado_unico)) {
          return null;
     }
       





        return $Encarregado_unico;
        }







 

    public static function MatricululaorConfirmacao($student_id,$anolectivo_id,$classe_id)
        {



        $EstudanteDetalhes_unico = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->select('ConfirmacaoPreco','MatriculaPreco','users.id as student_id')
            ->where(['mensalidade.Classe_id'=>$classe_id,'mensalidade.Anolectivo_id'=>$anolectivo_id,'estudante_x_ano_x_classe.student_id'=>$student_id])
            ->first();

            $ConfirmacaoOrMatriculaPreco = DB::table('estudante_x_ano_x_classe')
            ->where(['student_id' => $student_id])
            ->count();

            if ($ConfirmacaoOrMatriculaPreco === 1) {

            return [ 
                'Preco' => $EstudanteDetalhes_unico->MatriculaPreco,
                'Servico'=>'Matricula',

   ];

            } elseif ($ConfirmacaoOrMatriculaPreco > 1) {
            // Second time or more
                       return [ 
                'Preco' => $EstudanteDetalhes_unico->MatriculaPreco,
                'Servico' =>'Confirmacao',];

            }





        }








    public static function EstudanteDeAserConfirmado($student_id)
    {






        $Estudante = DB::table('users')
        ->Leftjoin('pessoa', 'pessoa.id', '=', 'users.pessoa_id')
        ->where('users.id', '=',$student_id)
          ->select('users.id', DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"), 'users.reg_Numero', 'users.email','users.dataofbirth','pessoa.tipoDeDocumento','pessoa.numeroDoDocumento','users.numeroDotelefone','genero_id','pessoa.pais')
        ->first();





        return  $Estudante;
    }













}
