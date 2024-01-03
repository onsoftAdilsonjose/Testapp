<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    

    protected $table = 'curso'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			// 'Pagamento_anual',
			// 'classe_id',
			// 'anolectivo_id',
			'nomeCurso',
            // 'ConfirmacaoPreco',
            // 'MatriculaPreco',

        // Outros campos aqui
    ];




    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
 