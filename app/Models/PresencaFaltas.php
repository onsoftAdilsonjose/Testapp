<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresencaFaltas extends Model
{
           protected $table = 'attendance'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'idattendance_types',
			'studentID',
			'classeID',
			'anolectivoID',
            'attendance_date',
            'disciplinaID',
            

        // Outros campos aqui
    ];
}
