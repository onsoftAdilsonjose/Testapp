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

class PropinaspagasController extends Controller
{
    //
    public function propinaspagas(Request $request){


// tipoRelatorioId:1 --- pagamentio de propina 
// tipoRelatorioId:2 --- pagamentio de servico
// categoriaRelatorioId:1----- categoria de araelatorio resumido
// categoriaRelatorioId:2----- categoria de araelatorio nao resumido
$relatoriodePagamento = [];
$relatoriodePagamentoServico = [];
// anoLectivoID
// categoriaRelatorioId
// classeID
// cursoID
// dataFinal
// dataInicial 
// periodoID
// salaID
// turmaID



    // "categoriaRelatorioId": 2,
    // "tipoRelatorioId": 1,
    // "dataFinal": "2023-12-12",
    // "dataInicial": "2023-12-06",
    // "classeID": 11,
    // "cursoID": 2,
    // "periodoID": 2,
    // "anoLectivoID": 1,
    // "turmaID": 4,
    // "salaID": 9



if ($request->tipoRelatorioId == 1) {
$relatoriodePagamento = DB::table('payments')
->join('transactions', 'transactions.payment_id', '=', 'payments.id')
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'payments.anolectivoID')
->join('classes', 'classes.id', '=', 'payments.classID')
->join('users', 'users.id', '=', 'payments.studentID')
->join('meses', 'meses.id', '=', 'transactions.MesesID')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=','users.id')
->join('curso', 'curso.id', '=','estudante_x_ano_x_classe.Curso_id')
->join('users as funcionarios', 'funcionarios.id', '=', 'payments.FocionarioID')
->when($request->classeID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Classe_id' => $request->classeID]);
})

->when($request->cursoID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Curso_id' =>$request->cursoID]);
})
->when($request->periodoID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Periodo_id' =>$request->periodoID]);
})
->when($request->turmaID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Turma_id' =>$request->turmaID]);
})

->when($request->dataInicial && $request->dataFinal, function ($query) use ($request) {
return $query->whereBetween('payments.created_at', [$request->dataInicial, $request->dataFinal]);
})
->select(
DB::raw("MAX(CONCAT(users.ultimo_nome, ' ', users.primeiro_nome)) as nomeCompleto"), 
'nomeCurso',
'ano_lectivo',
'classe_name',
'payments.id as payment_id',
'transactions.studentID',
'transactions.classID',
'transactions.anolectivoID',
'transactions.paymentOrder',
'FocionarioID',
DB::raw('COUNT(mesNome) as Qtd'),
DB::raw('SUBSTRING(MIN(mesNome), 1, 3) as firstMesNome'),
DB::raw('SUBSTRING(MAX(mesNome), 1, 3) as lastMesNome'),
DB::raw('SUM(transactions.Preco) as totalPreco'),
DB::raw('SUM(transactions.Multa) as totalMulta'),
DB::raw('SUM(transactions.Descount) as totalDescount'),
DB::raw("MAX(CONCAT(funcionarios.ultimo_nome, ' ', funcionarios.primeiro_nome)) as operador"),)
->groupBy('payments.id', 'transactions.studentID', 'transactions.classID', 'transactions.anolectivoID', 'transactions.paymentOrder','classe_name','ano_lectivo','nomeCurso','FocionarioID')
->get();


// Calculate the sum of totalDescount + totalMulta + totalPreco
$totalSum = $relatoriodePagamento->sum(function ($item) {
return $item->totalMulta + $item->totalPreco - $item->totalDescount;
});

} elseif($request->tipoRelatorioId== 2) {
$relatoriodePagamentoServico = DB::table('payments')
->join('transatiosservico', 'transatiosservico.payment_id', '=', 'payments.id')
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'payments.anolectivoID')
->join('classes', 'classes.id', '=', 'payments.classID')
->join('users', 'users.id', '=', 'payments.studentID')
->join('servicos', 'servicos.id', '=', 'transatiosservico.servicoID')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=','users.id')
->join('curso', 'curso.id', '=','estudante_x_ano_x_classe.Curso_id')
->join('users as funcionarios', 'funcionarios.id', '=', 'payments.FocionarioID')
->where(['payments.Cancelar'=>0,])
->when($request->classeID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Classe_id' => $request->classeID]);
})

->when($request->cursoID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Curso_id' =>$request->cursoID]);
})
->when($request->periodoID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Periodo_id' =>$request->periodoID]);
})
->when($request->turmaID, function ($query) use ($request) {
return $query->where(['estudante_x_ano_x_classe.Turma_id' =>$request->turmaID]);
})

->when($request->dataInicial && $request->dataFinal, function ($query) use ($request) {
return $query->whereBetween('payments.created_at', [$request->dataInicial, $request->dataFinal]);
})
->select(
 DB::raw("MAX(CONCAT(users.ultimo_nome, ' ', users.primeiro_nome)) as nomeCompleto"),    
'nomeCurso',
'ano_lectivo',
'classe_name',
'payments.id as payment_id',
'transatiosservico.studentID',
'transatiosservico.classID',
'transatiosservico.anolectivoID',
'transatiosservico.paymentOrder',
'FocionarioID',
'ServicoNome',
'Quantidade',
DB::raw('SUM(transatiosservico.Preco) as totalPreco'),
DB::raw('SUM(transatiosservico.Descount) as totalDescount'),
DB::raw("MAX(CONCAT(funcionarios.ultimo_nome, ' ', funcionarios.primeiro_nome)) as operador"), 
)
->groupBy('payments.id', 'transatiosservico.studentID', 'transatiosservico.classID', 'transatiosservico.anolectivoID', 'transatiosservico.paymentOrder','classe_name','ano_lectivo','nomeCurso','FocionarioID','ServicoNome','Quantidade')
->get();
// Calculate the sum of totalDescount + totalMulta + totalPreco
$totalSum = $relatoriodePagamentoServico->sum(function ($item) {
return $item->totalPreco - $item->totalDescount;
});

}















 
return response()->json([
    'propinas' =>$relatoriodePagamento,
    'servico'=>$relatoriodePagamentoServico,
    'totalSum' =>$totalSum,
], 200);


 

    }
}
