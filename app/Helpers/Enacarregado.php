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
use App\Estudante\EstudanteInfounico;
use App\Helpers\Trimestre;
class Enacarregado
{


public static function EnacarregadoFilhos($AnoLectivo){
$id = Auth::id();

$filhos = DB::table('estudante_x_ano_x_classe')
->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id') // Uncomment this line if needed
->where(['encarregadoID' => $id,'estudante_x_ano_x_classe.Anolectivo_id'=>$AnoLectivo])
->select('users.id as id', DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"))
->get();
return $filhos;

}




public static function filhoInfoanolectivo($anolectivo,$tudentid){

$id = Auth::id();

$detalhes= Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')

->where([
'users.id' => $tudentid,
'Anolectivo_id'=>$anolectivo,
'users.encarregadoID'=>$id
])
->select(   
        'student_id',
        'Periodo_id' ,
        'Turma_id' ,
        'Sala_id',
        'Classe_id',
        'Curso_id',
        'Anolectivo_id')
->first();



return $detalhes;


} 









public static function FilhosAnolectivo(){
$encarregadoID = Auth::id();
$AnoLectivo = AnoLectivo::join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'ano_lectivos.id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->where([

    //'estudante_x_ano_x_classe.student_id'=>$id,

    'users.encarregadoID'=>$encarregadoID
])
->select('ano_lectivo','ano_lectivos.id as anolectivoid')
    ->distinct()
    ->get('id');
return $AnoLectivo;

}


public static function BoletimEnacrregado($trimestre,$anolectivoID,$tudentid,$classeId){


 $encarregadoID= Auth::id();
 $trimestrenome= Trimestre::TrimestreEscolher($trimestre);

// return [$trimestrenome,$MediaTrimestre];
$Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->where(['estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $tudentid,
'users.encarregadoID'=>$encarregadoID
])
->select('users.id as studentID', 'salas.id as salaID', 'turmas.id as turmaID', 'periodos.id as periodoID', 'classes.id as classeID', 'ano_lectivos.id as anolectivoID', 'users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo', 'classes.ClassComExam')
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
'livrode_notas.studentID' => $tudentid,
//     // 'users.usertype','=','Estudante'
])

        ->select(
            'disciplinas.nomeDisciplina',
            "livrode_notas.Mac{$trimestre} as Mac",
            "livrode_notas.Npt{$trimestre} as Npt",
            "livrode_notas.Npp{$trimestre} as Npp",
            'livrode_notas.disciplinaID'
        )
        ->selectRaw("(livrode_notas.Mac{$trimestre} + livrode_notas.Npt{$trimestre} + livrode_notas.Npp{$trimestre}) / 3 AS Mediah");


if ($Estudante_x_Ano_x_Classe->ClassComExam != 0) {
$Disciplina = $Disciplina->addSelect('livrode_notas.Exam');
}
$Disciplina = $Disciplina->get();
return ['estudante' => $Estudante_x_Ano_x_Classe,
'boletimdenotas'=> $Disciplina];







}




public static function EncarregadoVerTodasNotas($anolectivoID,$classeId,$userId){

        $encarregadoID = Auth::id();

 
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $userId, 'users.encarregadoID' =>$encarregadoID ])
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
            //'Alunologado' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            //'$TrimestreCount'=>$TrimestreCount
        ]);












}

public static function notasencarregadohistorico($userId){






         $encarregadoID= Auth::id();

 
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
    ->where([
        'users.encarregadoID' => $encarregadoID,
        //'livrode_notas.anolectivoID' => $anolectivoID,
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
            //'Alunologado' => $Estudante_x_Ano_x_Classe,
            'Disciplina' =>$Disciplina,
            //'$TrimestreCount'=>$TrimestreCount
        ]);


}



}


