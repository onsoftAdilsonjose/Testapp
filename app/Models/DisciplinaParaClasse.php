<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinaParaClasse extends Model
{
      protected $table = 'disciplinaparaclasse'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da chave primária na tabela
    public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
			'Professor_id',
			'Disciplina_id','Periodo_id','Turma_id','Sala_id','Classe_id','Curso_id','Anolectivo_id',

        // Outros campos aqui
    ];



    
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'Disciplina_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'Periodo_id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'Turma_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'Sala_id');
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'Anolectivo_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'Classe_id');
    }
}
