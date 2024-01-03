<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
class ConfirmacaoController extends Controller
{


	
   public function ConfirmarGet($reg_Numero)
{


$EstudanteConfirmacao= DB::table('estudante_x_ano_x_classe')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->where(['users.status' => 0])
->where(['users.reg_Numero' => $reg_Numero])
->select(
	'users.id',
	'users.reg_Numero',
	'users.primeiro_nome',
	'users.ultimo_nome',
	'users.dataofbirth',
	'nomeCurso',
	'nomePeriodo',
	'nomeTurma',
	'nomeSala',
	'classe_name',
	'ano_lectivo',
	'ano_lectivos.id as Anolectivo_id','classes.id as Classe_id'
)
->first();
// se o estudante nao existe entao faremos verificaco nos estudante nao matriculado se tambem nao existem 
// dai retornaremos com error 404
if ($EstudanteConfirmacao == null && !$EstudanteConfirmacao ) {
$estudantenaomatriculado= DB::table('users')
->where(['users.status' => 0])->where(['users.reg_Numero' => $reg_Numero])
->select('users.id','users.reg_Numero','users.primeiro_nome','users.ultimo_nome','users.dataofbirth')->first();
 return response()->json(['EstudanteConfirmacao' => $estudantenaomatriculado], 200);
  



if ($estudantenaomatriculado == null && !$estudantenaomatriculado) {
    return response()->json(['error' => 'Aluno não encontrado or por favor Verificar O numero de Registro do Estudante'], 404);
}








}



// $SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($EstudanteConfirmacao->Classe_id,$EstudanteConfirmacao->Anolectivo_id,$EstudanteConfirmacao->id);


// $MesComDivida = Pagamento::MesesComDivida($EstudanteConfirmacao->id, $EstudanteConfirmacao->Anolectivo_id, $EstudanteConfirmacao->Classe_id,$SingleStudentDetalhes);
// $PagamentoMensal = Pagamento::PagamentoMensal($EstudanteConfirmacao->Anolectivo_id, $EstudanteConfirmacao->Classe_id,$SingleStudentDetalhes);
// $CountMesComDivida = count($MesComDivida);
// $Divida = round($CountMesComDivida * $PagamentoMensal);
// // $EstudanteConfirmacao->MesComDivida = $MesComDivida; 
// $EstudanteConfirmacao->PagamentoMensal =$PagamentoMensal;
// $EstudanteConfirmacao->CountMesComDivida = count($MesComDivida);
// $EstudanteConfirmacao->$Divida= round($CountMesComDivida * $PagamentoMensal);

 

 return response()->json(['EstudanteConfirmacao' => $EstudanteConfirmacao], 200);



}




}
