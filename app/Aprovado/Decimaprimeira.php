<?php

namespace App\Aprovado;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;


class Decimaprimeira
{
   


public static function EstatusAprovadoOuReprovadoClasse10or11(){


/// para dectar se o aluno foi a provado ou nao teremo de usar uma logica
	// onde pegaremos todas as disciplinas de  e as disciplinas chave e nuclear com podemos 
	// ja com esta funcao executar uma logica manuel onde iremos nos baseiar nas mnedias
	// trimestres para classe sem e media final com classe com exames.



$dados  = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('disciplinaparaclasse', 'disciplinaparaclasse.id', '=', 'livrode_notas.disciplinaID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->join('tipodesciplina', 'tipodesciplina.id', '=', 'disciplinaparaclasse.TipodeDisciplina_id')
        ->where([
        'livrode_notas.classeID' => 11,
        'livrode_notas.studentID' => 254,
    ])
	->select('disciplinas.*','tipodesciplina.*','livrode_notas.*')
	->selectRaw('(livrode_notas.Mac1 + livrode_notas.Npt1 + livrode_notas.Npp1) / 3 AS MediaPrimeiroTrimestre')
	->selectRaw('(livrode_notas.Mac2 + livrode_notas.Npt2 + livrode_notas.Npp2) / 3 AS MediaSegundoTrimestre')
	->selectRaw('(livrode_notas.Mac3 + livrode_notas.Npt3 + livrode_notas.Npp3) / 3 AS MediaTerceriroTrimestre')
    ->get();
    
 
 
 
// Contadores para negativas
$negativasGerais = 0;
$negativasEspecificas = 0;

foreach ($dados as $disciplina) {
    $mediaPrimeiroTrimestre = ($disciplina['Mac1'] + $disciplina['Npt1'] + $disciplina['Npp1']) / 3;
    $mediaSegundoTrimestre = ($disciplina['Mac2'] + $disciplina['Npt2'] + $disciplina['Npp2']) / 3;
    $mediaTerceiroTrimestre = ($disciplina['Mac3'] + $disciplina['Npt3'] + $disciplina['Npp3']) / 3;

    // Calcular média final para cada disciplina
    $mediaFinal = ($mediaPrimeiroTrimestre + $mediaSegundoTrimestre + $mediaTerceiroTrimestre) / 3;

    // Verifica se é disciplina geral
    if ($disciplina['TipoNome'] === 'gerais') {
        if ($disciplina['nomeDisciplina'] === 'LÍNGUA PORTUGUESA') {
            // LÍNGUA PORTUGUESA não conta como negativa
            continue;
        }

        if ($mediaFinal < 9) {
            $negativasGerais++;
        }
    }

    // Verifica se é disciplina específica
    if ($disciplina['TipoNome'] === 'especifica') {
        if ($mediaFinal < 9) {
            $negativasEspecificas++;
        }
    }
}

// Condição de aprovação
if ($negativasGerais <= 2 && $negativasEspecificas <= 1) {
    echo "Aprovado";
} else {
    echo "Reprovado";
}


}






 











}


 