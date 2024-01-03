<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
   protected $table = 'payments'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
				//'reference_number',
				'ValorPago',
				'info',
				'studentID',
				'classID',
				'anolectivoID',
				'description',
				'paymentOrder',
				'FocionarioID',
				'Descount',
				'Cancelar',
				'TipodePagementoID',
				'bancoid',
				'InvoiceType',
				'fc'//factura cancelada id 


        // Outros campos aqui
    ];
}
