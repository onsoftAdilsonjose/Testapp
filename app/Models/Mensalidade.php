<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
        protected $primaryKey = 'id';
		protected $table = 'mensalidade';
		protected $fillable = [
        'Propina_Anual','Classe_id','Curso_id','Anolectivo_id','ConfirmacaoPreco','MatriculaPreco',
        'Periodo_id','Turma_id','Sala_id'];


        public $timestamps = false;
}
