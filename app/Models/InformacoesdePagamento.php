<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacoesdePagamento extends Model
{
    use HasFactory;

    protected $table = 'multa'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
 
    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
				'Nome',
				'percetagem',
				'diaCombraca',
				'Desconto'
        // Outros campos aqui
    ];

     public $timestamps = false;
}
