<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;
class BoletimController extends Controller
{
    //



public function BoletimDeNotasprimeiro($classeId, $anolectivoID, $studentID)
{
    $Aluno = DB::table('users')
        ->join('livrode_notas', 'livrode_notas.studentID', '=', 'users.id')
        ->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
        ->where('studentID', '=', $studentID)
        ->where('anolectivoID', '=', $anolectivoID)
        ->where('classeID', '=', $classeId)
        ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'disciplinas.nomeDisciplina',
            'Mac1', 'Npp1', 'Npt1', 
            DB::raw('(Mac1 + Npp1 + Npt1) / 3 as media_final1')
        )
        ->get();

 if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}






public function BoletimDeNotassegundo($classeId, $anolectivoID, $studentID)
{
    $Aluno = DB::table('users')
        ->join('livrode_notas', 'livrode_notas.studentID', '=', 'users.id')
        ->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
        ->where('studentID', '=', $studentID)
        ->where('anolectivoID', '=', $anolectivoID)
        ->where('classeID', '=', $classeId)
        ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'disciplinas.nomeDisciplina',
            'Mac2', 'Npp2', 'Npt2', 
            DB::raw('(Mac2 + Npp2 + Npt2) / 3 as media_final2')
        )
        ->get();

  if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}







public function BoletimDeNotasterceiro($classeId, $anolectivoID, $studentID)
{
    $Aluno = DB::table('users')
        ->join('livrode_notas', 'livrode_notas.studentID', '=', 'users.id')
        ->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
        ->where('studentID', '=', $studentID)
        ->where('anolectivoID', '=', $anolectivoID)
        ->where('classeID', '=', $classeId)
        ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'disciplinas.nomeDisciplina',
            'Mac3', 'Npp3', 'Npt3', 
            DB::raw('(Mac3 + Npp3 + Npt3) / 3 as media_final3')
        )
        ->get();

    if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}







public function DeclaracaoComNotas($classeId, $anolectivoID, $studentID)
{
    $Aluno = DB::table('users')
        ->join('livrode_notas', 'livrode_notas.studentID', '=', 'users.id')
        ->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
        ->where('studentID', '=', $studentID)
        ->where('anolectivoID', '=', $anolectivoID)
        ->where('classeID', '=', $classeId)
        ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'disciplinas.nomeDisciplina',
            'Mac1', 'Npp1', 'Npt1',
        	'Mac2', 'Npp2', 'Npt2',
        	'Mac3', 'Npp3', 'Npt3',


    )
        ->get();

   if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}




public function DeclaracaoSemNotas($classeId, $anolectivoID, $studentID)
{
    $Aluno = DB::table('users')
        ->join('livrode_notas', 'livrode_notas.studentID', '=', 'users.id')
        ->join('disciplinas', 'disciplinas.id', '=', 'livrode_notas.disciplinaID')
        ->where('studentID', '=', $studentID)
        ->where('anolectivoID', '=', $anolectivoID)
        ->where('classeID', '=', $classeId)
        ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'disciplinas.nomeDisciplina')
        ->get();

 if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}


public function GuiadeTransferencia($classeId, $anolectivoID, $studentID)
{
		$Aluno = DB::table('users')
		->join('pessoa', 'pessoa.id', '=', 'users.id')
		->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
		->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
		->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
        ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
          ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
           ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
          ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
		->where('estudante_x_ano_x_classe.student_id', $studentID)
		->where('estudante_x_ano_x_classe.Classe_id', $classeId)
		->where('estudante_x_ano_x_classe.Anolectivo_id', $anolectivoID)
        // ->where('anolectivoID', '=', $anolectivoID)
        // ->where('classeID', '=', $classeId)
   ->select('users.reg_Numero', 'users.primeiro_nome', 'users.ultimo_nome', 'users.dataofbirth', 'nomeMae', 'nomePai', 'pais', 'num_bilhete', 'num_cedula', 'bairro', 'n_passaport', 'classe_name', 'nomeCurso', 'nomeTurma', 'nomeSala', 'nomePeriodo', 'ano_lectivo')->first();

    if (!$Aluno) {
        return response()->json(['error' => 'Aluno não encontrado or por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
    }

    return response()->json(['Aluno' => $Aluno]);
}



}
