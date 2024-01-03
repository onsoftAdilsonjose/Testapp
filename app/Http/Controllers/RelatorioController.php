<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Payment;
use App\Models\Transactions;
use App\Models\Servico;
use App\Models\TransatiosServico;
use App\Pagamentos\PagarFunctionExtras;

class RelatorioController extends Controller
{
    //








  public function todosRelatorio(){
      








$todosRelatorio = DB::table('payments')->select(
    'payments.id',
    'users.id as studentId',
    'classes.id as classeId',
    'ano_lectivos.id as anolectivoId',
    DB::raw("CONCAT(ultimo_nome, ' ', primeiro_nome) as nomeCompleto"),
    'ano_lectivo',
    'classe_name',
    'paymentOrder',
    'ValorPago',
    DB::raw("CASE WHEN Cancelar = 1 THEN 'canceled' ELSE 'active' END AS Cancelar")
)
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'payments.anolectivoID')
->join('classes', 'classes.id', '=', 'payments.classID')
->join('users', 'users.id', '=', 'payments.studentID')
->get();




        return response()->json(['todosRelatorio' =>  $todosRelatorio]);


}





  public function unicoRelatorio($unicoRelatorioId){


 
$Factura = PagarFunctionExtras::Factura($unicoRelatorioId);
 

return response()->json([
'Pagamento' => $Factura
]);



}







 

  public function relatoriodePagamento($anolectivoID,$studentID){

$relatoriodePagamento = DB::table('payments')
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->join('invoicetype', 'invoicetype.id', '=', 'payments.InvoiceType')
->select(
'payments.id as id',
'InvoiceType',
'ValorPago',
'Tipodepagamento',
'paymentOrder',
'Descount',
'invoicetype.nome',
'Cancelar as Status',
'SaldoGuardado',
'SaldoRemovido',

)
->where(['payments.studentID'=>$studentID, 'payments.anolectivoID'=>$anolectivoID])
->get();



$users = DB::table('users')
    ->where(['users.id' => $studentID]) // Filters based on the provided student ID
    ->select(DB::raw("CONCAT(ultimo_nome, ' ', primeiro_nome) as nomeCompleto"), 'users.reg_Numero')
    ->first();


return response()->json([
'relatoriodePagamento' => $relatoriodePagamento,
'user'=>$users

]);


}














  public function relatoriopaymentOrderdetalhes($paymentOrder){


  







}



 



  public function FacturaUnicadoEstudante($paymentid){





$Factura = PagarFunctionExtras::Factura($paymentid);
 

return response()->json([
'Pagamento' => $Factura
]);












}







}
