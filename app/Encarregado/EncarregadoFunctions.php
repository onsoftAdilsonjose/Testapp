<?php

namespace App\Encarregado;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;
use App\Models\AnoLectivo;
use App\Models\Meses;


class EncarregadoFunctions
{
   


//aqui onde nos vamos encotra estudante com classe atravez do ano lectivo
public static function getstudentInfoEncarregado($anolectivo,$tudentid){
$userId = Auth::id();
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
'encarregadoID'=>$userId
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





}


 