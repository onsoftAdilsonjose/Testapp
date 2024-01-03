<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class classes extends Model
{




 protected $table = 'classes'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'classe_name',
			'ClassComExam',
    ];





}
