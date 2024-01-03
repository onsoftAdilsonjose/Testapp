<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoDisciplinar extends Model
{
protected $table = 'processodisciplinar'; // Nome da tabela no banco de dados
protected $primaryKey = 'id'; // Nome da chave primária na tabela
public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

// Defina os campos que podem ser preenchidos em massa
protected $fillable = [
'motivo',
'student_id',
'registradopor',
'testemunha',
'data',
'Classe_id',
'Anolectivo_id',


// Outros campos aqui
];
}



