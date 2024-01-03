<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
        protected $table = 'livrode_notas'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
    	    'disciplinaID',
			'Mac1',
			'Npp1',
			'Npt1',
			'Mac2',
			'Npp2',
			'Npt2',
			'Mac3',
			'Npp3',
			'Npt3',
			'Exam',
			'classeID',
			'studentID',
			'anolectivoID',
			'salaID',
			'turmaID',
			'periodoID',
			'USERID',
			'CursoID',


        // Outros campos aqui
    ];
}
 
