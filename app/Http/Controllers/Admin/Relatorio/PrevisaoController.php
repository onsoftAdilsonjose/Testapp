<?php

namespace App\Http\Controllers\Admin\Relatorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Payment;
use App\Models\Transactions;
use App\Models\Servico;
use App\Models\TransatiosServico;
use App\Pagamentos\PagarFunctionExtras;
use App\Pagamentos\RelatorioFunctionExtras;
use App\MyCustomFuctions\Months;
use App\Models\User;

class PrevisaoController extends Controller
{ 
    //
    public function Previsaodepagamento(Request $Request)
{
    $startDate = "2023-06-28";



$result = Months::MesesFunc($startDate);
$monthNumber= $result[1];


    $usersWithoutTransactions = User::join('transactions', 'users.id', '=', 'transactions.studentID')
        ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
        ->join('meses', 'meses.mesID', '=', 'transactions.MesesID')
        ->where(['users.usertype' => 'Estudante'])
        ->whereIn('MesesID', [$monthNumber])
        ->select('users.primeiro_nome', 'users.id as id')
        ->get();
 

$query = User::whereNotIn('users.id', $usersWithoutTransactions->pluck('id')->toArray())
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->select(   DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS nomeCompleto"),
    
            "student_id",
            "Periodo_id",
            "Turma_id",
            "Sala_id",
            "Classe_id",
            "Curso_id",
            "Anolectivo_id",
            "nomeCurso",
            "tipodecursoID",
            "nomePeriodo",
            "nomeTurma",
            "nomeSala",
            "classe_name",
            "ano_lectivo")
->where(['users.usertype' => 'Estudante']);

$usersCount = $query->count();
$userswithdue = $query->get();





foreach ($userswithdue as $userswithdues) {
$dividamensal = RelatorioFunctionExtras::getmensalprice($userswithdues->Classe_id,$userswithdues->Anolectivo_id,$userswithdues->student_id);
$userswithdues->pagamentomnesal = $dividamensal;
$userswithdues->MesNome = $result[0];
$userswithdues->Qtd = 1;

}

        return response()->json([
        'dividas' =>$userswithdue,
        'totaldivida'=>$dividamensal*$usersCount,
        ], 200);
}

}
