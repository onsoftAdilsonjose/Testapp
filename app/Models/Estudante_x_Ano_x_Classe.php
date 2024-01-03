<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudante_x_Ano_x_Classe extends Model
{

  protected $primaryKey = 'id';
		protected $table = 'estudante_x_ano_x_classe';
		protected $fillable = [

'student_id',
'Periodo_id',
'Turma_id',
'Sala_id',
'Classe_id', 
'Curso_id',
'Anolectivo_id',
'desistente',
   
 

];
}
