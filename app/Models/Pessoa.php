<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
        protected $table = 'pessoa'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'tipoDeDocumento',
			'pais',
			'reg_Numero',
			'municipio_id',
			'bairro',
			'provincia_id',
			'genero_id',
			'numeroDoDocumento',
		];









 




			

}
