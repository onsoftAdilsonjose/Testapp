<?php

namespace App\Http\Controllers\Barcode;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Payment;
use App\Models\Estudante_x_Ano_x_Classe;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Milon\Barcode\Classes\BarcodeGenerator;
use App\MyCustomFuctions\AprovadoOrReprovado;
use App\Pagamentos\RelatorioFunctionExtras;
use App\MyCustomFuctions\Months;
class ScanController extends Controller
{
    //

 


public function Scanestudante(Request $request){
 
//ES23101957311

try {

	$Barcode = strtoupper($request->data);

    $aluno = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
        ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
        ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
        ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
         ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
          ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
        ->select(   
            DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
            'reg_Numero',
            'dataofbirth',
            'nomePeriodo' ,
            'classe_name',
            'nomeCurso',
            'nomeSala',
            'nomeTurma',
            'ano_lectivo',
            'Periodo_id',
            'Turma_id' ,
            'Sala_id',
            'Classe_id',
            'Curso_id',
            'Anolectivo_id',
            'users.id as id'
        )
        ->where(['users.reg_Numero' =>$Barcode])
        ->first();

    // Check if a record was found
    if ($aluno) {
				$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($aluno->Classe_id,$aluno->Anolectivo_id,$aluno->id);
				$months = Pagamento::Months($aluno->Classe_id,$SingleStudentDetalhes);
				$MesComDivida = Pagamento::MesesComDivida($aluno->id, $aluno->Anolectivo_id, $aluno->Classe_id,$SingleStudentDetalhes); 
				$MesesPago = Pagamento::MesesPago($aluno->id, $aluno->Anolectivo_id, $aluno->Classe_id);
				$PagarApartir = Pagamento::PagarApartir($aluno->id, $aluno->Anolectivo_id, $aluno->Classe_id);
				$PagamentoMensal = Pagamento::PagamentoMensal($aluno->Anolectivo_id,$aluno->Classe_id,$SingleStudentDetalhes);
				$CountMesComDivida = count($MesComDivida);
				$Divida =$CountMesComDivida * $PagamentoMensal;
				$resultArray = MinhasFuncoes::checkMonths($months, $aluno->Anolectivo_id, $aluno->id, $aluno->Classe_id,$SingleStudentDetalhes);
				$MesesComMultas = round($resultArray['totalCount'],2);
				$mesesIDComMulta = $resultArray['MesesComMultas'];
				$ValorDaMulta = MinhasFuncoes::calcularjuros($MesesComMultas * $PagamentoMensal);
				$totalcommulta = $MesesComMultas * $PagamentoMensal +$ValorDaMulta  ;
                $multapormes =$ValorDaMulta/$MesesComMultas;


                $status = ($CountMesComDivida) ? true : false ;




				return response()->json([
				'status'=>$status,	
				'success'=>true,	
				'multapormes'=>$multapormes,
				'ValorDaMulta'=>$ValorDaMulta,
				'MesesComMultas'=>$MesesComMultas,
				'Divida'=>$Divida,
				'CountMesComDivida'=>$CountMesComDivida,	
                'MesComDivida'=>$MesComDivida,
                'aluno'=>$aluno,
                'resultArray'=>$resultArray,
                'totalcommulta'=>$totalcommulta
               

				],200);






    } else {
        // Handle the case where no matching record was found
        return response()->json(['error' => 'Nenhum registro correspondente encontrado'], 404);
    }
} catch (\Exception $e) {
    // Handle the exception, log the error, or return an error response
    return response()->json(['error' => $e->getMessage()], 500);
}


}




public function VerestudanteComMultas(){



return view('Estudante');


}





public function Listaestudante(){

$aluno= Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->select(DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
'reg_Numero',
'dataofbirth',
'nomePeriodo' ,
'classe_name',
'nomeCurso',
'ano_lectivo')
->get();


 


return view('Listaestudante',['estudantes'=>$aluno]);


}


public function aprovado(){

$unicoRelatorioId = 1;


$unicoRelatorio = DB::table('tipodepagamento')
->join('payments','payments.TipodePagementoID','=','tipodepagamento.id')
// ->Leftjoin('banco','.banco.id','=','payments.bancoid')
->select('payments.paymentOrder','ValorPago','tipodepagamento',)
->first();

$propina = DB::table('transactions')
    ->join('meses', 'meses.id', '=', 'transactions.MesesID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'transactions.anolectivoID')
    ->select(
        DB::raw("CASE WHEN Cancelar = 1 THEN 'canceled' ELSE 'active' END AS Cancelar"),
        'ano_lectivo',
        'mesNome',
        'Multa',
        'Preco',
        'Descount',
        'anolectivoID',
        DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((Preco + Multa - Descount), 2) END as propinaTotal')
    )
    ->where('payment_id', '=', $unicoRelatorioId)
    ->orderBy('orderNumber', 'ASC') // Add this line to order by orderNumber
    ->get();

$servico = DB::table('transatiosservico')
    //->join('servicos', 'servicos.id', '=', 'transatiosservico.servicoID')
    ->select(
        'transatiosservico.Quantidade',
        'transatiosservico.Preco',
       // 'servicos.ServicoNome',
        'transatiosservico.Descount',
        'transatiosservico.payment_id',
        'servicoID',
        DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((transatiosservico.Preco * transatiosservico.Quantidade - Descount), 2) END as servicoTotal')
    )
    ->where('payment_id', '=', $unicoRelatorioId)
    ->get();

$matricula = DB::table('registro')
    ->where(['payment_id'=>$unicoRelatorioId,])
    ->select('matriculaorconfirmacaoId as servico','Preco')
    ->first();

 
 
$unicoRelatorio->matricula=$matricula;
$unicoRelatorio->servico =$servico;
$unicoRelatorio->propina  =$propina ;







// Now you have the combined data in $combinedData and the total amount in $totalAmount


return response()->json([

'Pagamento'=>$unicoRelatorio

]);


 

 
}






 // previsao de pagamento de Propinas
public function testarfunctions()
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
->select(   DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS registradopor"),
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





              
public function AlunosDevedores(){




$months = DB::table('meses')->get();




$meses = MinhasFuncoes::checkMonths($months, $anolectivoID, $studentID, $classID,3);





}











}














 
