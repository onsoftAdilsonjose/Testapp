<?php

namespace App\Helpers;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;
use App\Models\AnoLectivo;
use App\Models\Meses;


class Ajuda
{
   

public static function FilterAnolectivoEstudanteloggado(){


$id = Auth::id();

$AnoLectivo = AnoLectivo::join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'ano_lectivos.id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->where(['estudante_x_ano_x_classe.student_id'=>$id])      
->select('ano_lectivo','ano_lectivos.id as anolectivoid','classe_name','classes.id as classeid'
)
->get();



return $AnoLectivo;

}







public static function Boletimtrimestras($trimestre,$anolectivoID,$classeId){

 $userId = Auth::id();
 
       

 
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $userId ])
            ->select('users.id as studentID', 'salas.id as salaID', 'turmas.id as turmaID', 'periodos.id as periodoID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo', 'classes.ClassComExam')
            // ->where()
            ->first();
$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    //->join('disciplinaparaclasse', 'disciplinaparaclasse.Professor_id', '=', 'users.id')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([
        'livrode_notas.classeID' => $classeId,
        'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $userId,
        // 'users.usertype','=','Estudante' 
    ])
   ->select(
        'disciplinas.nomeDisciplina',
        "livrode_notas.Mac{$trimestre} as Mac",
        "livrode_notas.Npt{$trimestre} as Npt",
        "livrode_notas.Npp{$trimestre} as Npp",
        'livrode_notas.disciplinaID'
    )
 ->selectRaw("(livrode_notas.Mac{$trimestre} + livrode_notas.Npt{$trimestre} + livrode_notas.Npp{$trimestre}) / 3 AS Media");


if ($Estudante_x_Ano_x_Classe->ClassComExam != 0) {
    $Disciplina = $Disciplina->addSelect('livrode_notas.Exam');
}
    $Disciplina = $Disciplina->get();

        foreach ($Disciplina as $disciplina) {
        $disciplina->Media = number_format($disciplina->Media, 2);
    }

return $Disciplina;

}  








public static function Todasnotas($anolectivoID,$classeId){






        $userId = Auth::id();

 
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $userId ])
            ->select('users.id as studentID', 'salas.id as salaID', 'turmas.id as turmaID', 'periodos.id as periodoID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo', 'classes.ClassComExam')
            // ->where()
            ->first();
$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')

    //->join('disciplinaparaclasse', 'disciplinaparaclasse.Professor_id', '=', 'users.id')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([
        'livrode_notas.classeID' => $classeId,
        'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $userId,
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


      // $TrimestreCount =  Months::Trimestre($anolectivoID);

          return response()->json([
            'Alunologado' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            //'$TrimestreCount'=>$TrimestreCount
        ]);












}


public static function historico(){






        $userId = Auth::id();

 
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where([ 'users.id' => $userId ])
            ->select('users.id as studentID', 'salas.id as salaID', 'turmas.id as turmaID', 'periodos.id as periodoID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo', 'classes.ClassComExam')
            // ->where()
            ->first();
$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    //->join('disciplinaparaclasse', 'disciplinaparaclasse.Professor_id', '=', 'users.id')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->join('curso', 'curso.id', '=', 'livrode_notas.CursoID')
    ->where([
        //'livrode_notas.classeID' => $classeId,
        //'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $userId,
        // 'users.usertype','=','Estudante' 
    ])
    ->select(
        'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo',
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


      // $TrimestreCount =  Months::Trimestre($anolectivoID);

          return response()->json([
            'Alunologado' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            //'$TrimestreCount'=>$TrimestreCount
        ]);


}










}


 