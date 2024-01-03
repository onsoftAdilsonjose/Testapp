<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransatiosServico extends Model
{
     protected $table = 'transatiosservico'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
				'payment_id',
				'servicoID',
				'Preco',
				'studentID',
				'classID',
				'anolectivoID',
				'paymentOrder',
				'Quantidade',
				'Cancelar',
				'Descount',
        // Outros campos aqui
    ];

}
