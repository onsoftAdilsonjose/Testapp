<?php

namespace App\Aprovado;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;


class Quartaclasse
{
   

public static function EstatusAprovadoOuReprovadoClasse4(){


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
  


// Suponha que você tenha um array $notas contendo as notas do aluno

// Contadores para negativas
$negativas = 0;

// Flags para Português e Matemática
$portuguesNegativa = false;
$matematicaNegativa = false;

foreach ($dados as $disciplina) {
    // Verifica se é Português ou Matemática
    $isPortugues = ($disciplina['nomeDisciplina'] === 'LÍNGUA PORTUGUESA');
    $isMatematica = ($disciplina['nomeDisciplina'] === 'MATEMÁTICA');

    // Calcula a média final para cada disciplina
    $mediaFinal = ($disciplina['Mac1'] + $disciplina['Npt1'] + $disciplina['Npp1']) / 3;

    // Conta as negativas
    if ($mediaFinal < 9) {
        $negativas++;

        // Atualiza as flags para Português e Matemática
        if ($isPortugues) {
            $portuguesNegativa = true;
        } elseif ($isMatematica) {
            $matematicaNegativa = true;
        }
    }
}

// Condição de transição
if ($negativas <= 2 && !($portuguesNegativa && $matematicaNegativa)) {
    echo "Transita para a 5ª classe";
} else {
    echo "Reprovado na transição, verificar notas";
}


  

}






 











}


 