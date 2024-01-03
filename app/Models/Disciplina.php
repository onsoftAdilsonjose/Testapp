<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
  protected $table = 'disciplinas'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    // ProvaOral = 1 disciplina com prova oral 
    //ProvaOral = 0 disciplinasem  prova oral 
    protected $fillable = [
			'nomeDisciplina','ProvaOral',
        // Outros campos aqui
    ];
}
 