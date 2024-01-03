<?php

namespace App\Models;

 
use Illuminate\Database\Eloquent\Model;

class Meses extends Model
{
        protected $table = 'meses'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'mesNome',
			'mesID',
			'mesPercetagemDesconto',
			'mesAnularPagamento',
            'mesAnolectivoID',
            'ClassComExam',
            'Data',
            'orderNumber',
            

        // Outros campos aqui
    ];
}
