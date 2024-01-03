<?php

namespace App\Pagamentos;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;
use App\Models\AnoLectivo;
use App\Models\Meses;
use App\Models\InformacaoDaEscola;

class PagarFunctionExtras
{
   
 
 public static function infoschool(){
	$infoschool = InformacaoDaEscola::select('nomeDaempresa','numeroDaescola','endereco','nif','cidade','email','telefoneAlternativo','numeroDotelefone','Site','logo')->first();
	
  return $infoschool;


 }


 public static function infoestudante($estudentid,$anolectivo){
$infoestudante= Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->where([
'users.id' => $estudentid,
'Anolectivo_id'=>$anolectivo
])
->select(  
DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS nomeCompleto"),'reg_Numero',
'classe_name',
'nomeCurso',
'nomePeriodo',
'ano_lectivo',
'nomeSala',
'nomeTurma',
'ano_lectivo')
->first();
return $infoestudante;

 	
 }



 public static function infoantedence($userid){
 $infoantedence = DB::table('users')->where(['id' =>$userid])->select('primeiro_nome','ultimo_nome','usertype as usuario')->first();
 return  $infoantedence;
 	
 }





public static function Factura($unicoRelatorioId){
$unicoRelatorio = DB::table('tipodepagamento')
    ->join('payments', 'payments.TipodePagementoID', '=', 'tipodepagamento.id')
     ->join('invoicetype', 'invoicetype.id', '=', 'payments.InvoiceType')
     ->Leftjoin('banco','.banco.id','=','payments.bancoid') // commented out as it's not being used
    ->select('payments.paymentOrder', 'ValorPago', 'tipodepagamento', 'payments.id as id','BANCO','IBAN','NDECONTA','payments.created_at as datacriacao','InvoiceType')
    ->where('payments.id', '=', $unicoRelatorioId)
    ->first();


 


if  ($unicoRelatorio && $unicoRelatorio->InvoiceType == 2) {

  $unicoRelatorio = DB::table('tipodepagamento')
    ->join('payments', 'payments.TipodePagementoID', '=', 'tipodepagamento.id')
     ->join('invoicetype', 'invoicetype.id', '=', 'payments.InvoiceType')
     ->Leftjoin('banco','.banco.id','=','payments.bancoid') // commented out as it's not being used
    ->select('payments.paymentOrder', 'ValorPago', 'tipodepagamento', 'payments.fc as id','BANCO','IBAN','NDECONTA','payments.created_at as datacriacao','InvoiceType')
    ->where('payments.id', '=', $unicoRelatorioId)
    ->first();
}





$decode = DB::table('payments')->select('info','id')->where(['payments.id'=>$unicoRelatorioId])->first();
$decode->info = json_decode($decode->info, true);
$infoschool = $decode->info['infoschool'];
$infoestudante = $decode->info['infoestudante'];
$infoantedence = $decode->info['infoantedence'];

$unicoRelatorio->infoschool =$infoschool;
$unicoRelatorio->infoestudante =$infoestudante;
$unicoRelatorio->infoantedence =$infoantedence;








$propina = DB::table('transactions')
    ->join('meses', 'meses.id', '=', 'transactions.MesesID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'transactions.anolectivoID')
    ->select(
        // DB::raw("CASE WHEN Cancelar = 1 THEN 'canceled' ELSE 'active' END AS Cancelar"),
        // 'ano_lectivo',
        'mesNome',
        'Multa',
        'Preco',
        'Descount',
        // 'anolectivoID',
        // DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((Preco + Multa - Descount), 2) END as propinaTotal')
    )
    ->where('payment_id', '=', $unicoRelatorio->id)
    ->orderBy('orderNumber', 'ASC') // Add this line to order by orderNumber
    ->get();

$servico = DB::table('transatiosservico')
    ->join('servicos', 'servicos.id', '=', 'transatiosservico.servicoID')
    ->select(
        'transatiosservico.Quantidade',
        'transatiosservico.Preco',
        'servicos.ServicoNome',
        'transatiosservico.Descount',
        // 'transatiosservico.payment_id',
        // 'servicoID',
        // DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((transatiosservico.Preco * transatiosservico.Quantidade - Descount), 2) END as servicoTotal')
    )
    ->where('payment_id', '=', $unicoRelatorio->id)
    ->get();

$matricula = DB::table('registro')
    ->where(['payment_id'=>$unicoRelatorio->id])
    ->select('matriculaorconfirmacaoId as servico','Preco')
    ->first();

 $totalDescount = $propina->sum('Descount') + $servico->sum('Descount');

 
$unicoRelatorio->matricula=$matricula;
$unicoRelatorio->servico =$servico;
$unicoRelatorio->propina  =$propina;

$unicoRelatorio->totalDescount  =$totalDescount;


 
return $unicoRelatorio;
 


 

 
}





}












 
 