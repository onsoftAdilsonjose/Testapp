<?php

namespace App\Http\Controllers\Admin\Relatorio;
use NumberToWords\NumberToWords;
use DB;
use App\Models\Payment;
use App\Models\Transactions;
use App\Models\Servico;
use App\Models\TransatiosServico;
use App\Pagamentos\PagarFunctionExtras;
use App\MyCustomFuctions\Notification;
use App\MyCustomFuctions\Pagamento;
use App\Http\Controllers\Admin\Relatorio\RelatorionFunctions;

class RelatorionFunctions
{




public static function Devedores($studentIDs, $anolectivoID) {
 




$multa = DB::table('multa')->select('percetagem', 'diaCombraca')->first();
// $current = Carbon::today();
// $DAY = $multa->diaCombraca;
// $MONTH = $current->month;
// $YEAR = $current->year;
// Use Carbon to format the date with leading zeros for the day part
// $DaTaCombraca = Carbon::create($YEAR, $MONTH, $DAY)->format('Y-m-d');

$DaTaCombraca = [6,7,8,9,10,11,12];
///////os meses do request tem que ser passado nesta variavel 

  $allowedMesesIDs = DB::table('meses')
    ->where('mesAnolectivoID', '=', $anolectivoID)
    ->whereIn('mesID',$DaTaCombraca)
    ->pluck('mesID')
    ->toArray();

 $allowedMesesIDs;
    $studentsWithMissingTransactions = [];
    $studentData = [];


 // $estudantantecontador = count($studentIDs);
    foreach ($studentIDs as $student) {
        $existingMesesIDs = DB::table('transactions')
            ->join('meses', 'meses.mesID', '=', 'transactions.MesesID')
            ->where('studentID', $student->id)
            ->where('Cancelar', 0)
             ->whereIn('mesID',$DaTaCombraca)
            ->pluck('MesesID')
            ->toArray();

         $missingMesesIDs = array_diff($allowedMesesIDs, $existingMesesIDs);
//return $missingMesesIDs;
        $user = DB::table('users')
            ->Leftjoin('pessoa', 'pessoa.id', '=', 'users.pessoa_id')
            //->Leftjoin('users as enacarregado', 'pessoa.id', '=', 'users.pessoa_id')

            ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=','estudante_x_ano_x_classe.Anolectivo_id')
            ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('curso', 'curso.id', '=','estudante_x_ano_x_classe.Curso_id')
            ->where('users.id','=', $student->id)
            ->where(['estudante_x_ano_x_classe.Anolectivo_id' => $anolectivoID])
            ->select( DB::raw("CONCAT(users.ultimo_nome, ' ', users.primeiro_nome) as nomeCompleto"),'email','mensalidade.Classe_id','classe_name','mensalidade.id as studedetalhes','users.nomePai','users.nomeMae','users.numeroDotelefone','telefoneAlternativo','nomeCurso','curso.id as cursoid','ano_lectivo')
            ->first();


         foreach ($missingMesesIDs as $missingMesID) {
            $mesesInfo = DB::table('meses')->where('mesID', $missingMesID)->select('mesID', 'mesNome')->first();

            if (!isset($studentData[$student->id])) {

                $studentData[$student->id] = [
                    'studentID' => $student->id,
                    'studedetalhes'=>$student->studedetalhes,
                    'Classe_id' => $student->Classe_id,
                    'nomeCurso' => $student->nomeCurso,
                    'classe_name' => $student->classe_name,
                    'ano_lectivo' => $student->ano_lectivo,
                   // 'PagamentoMensal' => Pagamento::PagamentoMensal($anolectivoID, $student->Classe_id),
                    'email' => $student->email,
                    'nomeCompleto' => $student->nomeCompleto,
                    'nomePai' => $student->nomePai,
                    'telefoneAlternativo' => $student->telefoneAlternativo,
                    'mesData' => []
                ];
            }

            $studentData[$student->id]['mesData'][] = [
                'mesID' => $mesesInfo->mesID,
                'mesNome' => $mesesInfo->mesNome
            ];
        }
    }


$contarestudante =  count($studentData);
$pagamentoTotal = [];
    foreach ($studentData as $student) {

        $mergedMesData = [];
        foreach ($student['mesData'] as $mes) {
            $mergedMesData[] = [
                'mesID' => $mes['mesID'],
                'mesNome' => $mes['mesNome']
            ];
        }
 
        $PagamentoMensal = Pagamento::PagamentoMensal($anolectivoID, $student['Classe_id'],$student['studedetalhes']);
       
        $meseCount = count($mergedMesData);
       // $ValorDaMulta = MinhasFuncoes::calcularjuros($meseCount * $PagamentoMensal);
         $TotalApagar= $PagamentoMensal * $meseCount;
       // $TotalMulta = $ValorDaMulta * $meseCount;
       //$pagamentoTotal = $TotalMulta + $TotalApagar
       
         $pagamentoTotal = $contarestudante * $TotalApagar;

        $studentsWithMissingTransactions[] = [
        //'percetagem' => $multa->percetagem,  

        'TotalApagar' =>$TotalApagar,
        'PagamentoMensal'=>$PagamentoMensal,
        //'TotalMulta'=>$TotalMulta,
        'NumeroDeMeses' => $meseCount,
        'studentID' => $student['studentID'],
        'nomeCurso' => $student['nomeCurso'],
        'classe_name' => $student['classe_name'],
        'ano_lectivo' => $student['ano_lectivo'],
        'email' => $student['email'],
        'nomeCompleto' => $student['nomeCompleto'],
        'nomePai' => $student['nomePai'],
        'telefoneAlternativo'=>$student['telefoneAlternativo'],
        'mesData' => $mergedMesData
        ];
    }

    return ['estudantescomdividas'=>$studentsWithMissingTransactions,'pagamentoTotal'=>$pagamentoTotal];
}




}