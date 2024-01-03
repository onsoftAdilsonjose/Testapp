<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    use HasFactory;

            protected $table = 'transport'; // Nome da tabela no banco de dados
            protected $primaryKey = 'id'; // Nome da chave primária na tabela

            // Defina os campos que podem ser preenchidos em massa
            protected $fillable = [
            'nome_rota',
            'preco',
            'municipio',
            'bairro',
            'status',		 
            // Outros campos aqui
    ];
}
