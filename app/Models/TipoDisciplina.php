<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class TipoDisciplina extends Model
{
  protected $table = 'tipodesciplina'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'TipoNome',

        // Outros campos aqui
    ];
}
