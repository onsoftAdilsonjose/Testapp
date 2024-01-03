<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmacaOrMatricula extends Model
{
    use HasFactory;
          protected $table = 'registro'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
'student_id',
'Classe_id',
'Anolectivo_id',
'Preco',
'paymentOrder',
'payment_id',
'matriculaorconfirmacaoId',
'cancelar',

        // Outros campos aqui
    ];
}
