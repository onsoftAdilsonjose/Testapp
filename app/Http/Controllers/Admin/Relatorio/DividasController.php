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
use App\MyCustomFuctions\Notification;
use App\MyCustomFuctions\Pagamento;
use App\Http\Controllers\Admin\Relatorio\RelatorionFunctions;

class DividasController extends Controller
{
    //

 



public function EstudantesDividas(Request $request){

$studentIDs = DB::table('users')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
->join('curso', 'curso.id', '=','estudante_x_ano_x_classe.Curso_id')
->join('classes', 'classes.id', '=','estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=','estudante_x_ano_x_classe.Anolectivo_id')
->where(['usertype'=>'Estudante']) // Fetch users with roles 4 or 48
->select('users.id as id','Anolectivo_id', 'telefoneAlternativo',DB::raw("CONCAT(users.ultimo_nome, ' ', users.primeiro_nome) as nomeCompleto"), 'email','Classe_id','classe_name','users.nomePai','users.nomeMae','users.numeroDotelefone','telefoneAlternativo','nomeCurso','curso.id as cursoid','ano_lectivo')
->get();


foreach ($studentIDs as $studentID) {
$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($studentID->Classe_id,$studentID->Anolectivo_id,$studentID->id);
$studentID->studedetalhes= $SingleStudentDetalhes;
}




$anolectivoID = DB::table('ano_lectivos')->select('id')->first();
$Devedores = RelatorionFunctions::Devedores($studentIDs, $anolectivoID->id);


return response()->json(['Devedores' => $Devedores], 200);




}




















}
