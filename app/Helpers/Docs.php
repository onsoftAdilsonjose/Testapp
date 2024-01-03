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


class Docs
{
   


public static function showlivroaftersave($id){
$biblioteca = DB::table('biblioteca')
->join('curso', 'curso.id', '=', 'biblioteca.Curso_id')
->join('classes', 'classes.id', '=', 'biblioteca.Classe_id')
->join('users', 'users.id', '=', 'biblioteca.userid')
->select('biblioteca.id as id','livronome','nomeCurso','classe_name', 'author', 'book_pdf',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS AdicionouLivro"))
->where(['biblioteca.id'=>$id])
->first();
return $biblioteca;
}



 

public static function classepath($id){

$classespath = DB::table('classes')->select('classe_name')->where(['classes.id'=>$id])->first();
//chamar funacao para tirar o espaco
return $classespath;

}


public static function processodiscipinardoaluno($id){

$processodisciplinar = DB::table('processodisciplinar')
->join('users', 'users.id', '=', 'processodisciplinar.student_id')
->join('classes', 'classes.id', '=', 'processodisciplinar.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'processodisciplinar.Anolectivo_id')
->select('processodisciplinar.id as id','testemunha','motivo','data','ano_lectivo','classe_name',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS estudante"))
->where(['processodisciplinar.id'=>$id])
->first();

$registradopor = DB::table('processodisciplinar')
->join('users', 'users.id', '=', 'processodisciplinar.registradopor')
->select(DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS registradopor"))
->where(['processodisciplinar.id'=>$id])
->first();

$processodisciplinar->registradopor = $registradopor;

return $processodisciplinar;
}


}


 