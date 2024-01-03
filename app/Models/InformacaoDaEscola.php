<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacaoDaEscola extends Model
{
      protected $table = 'informacaoescola'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
				'nomeDaempresa',
				'numeroDaescola',
				'endereco',
				'nif',
				'pais',
				'cidade',
				'municipio',
				'bairro',
				'telefoneAlternativo',
				'numeroDotelefone',
				'email',
				'Site',
				'logo'


        // Outros campos aqui
    ];
}
