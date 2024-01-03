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
use App\MyCustomFuctions\Pagamento;
use App\Models\User;

class RelatorioFunctionExtras
{
   
 

public static function  getmensalprice($classeId,$anolectivoID,$studentID) {

 

$AlunoDetalhes= User::select('users.id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
->where([
    'users.id'=>$studentID,
    'estudante_x_ano_x_classe.Classe_id'=>$classeId,
    'estudante_x_ano_x_classe.Anolectivo_id'=>$anolectivoID
])
->select('Classe_id','Curso_id','Periodo_id','Turma_id','Sala_id','Classe_id','Anolectivo_id','users.id')
->first();




$mensalidadeDetalhesEstudante = DB::table('mensalidade')
->where([
'Classe_id'=>$AlunoDetalhes->Classe_id,
'Curso_id'=>$AlunoDetalhes->Curso_id,
'Periodo_id'=>$AlunoDetalhes->Periodo_id,
'Turma_id'=>$AlunoDetalhes->Turma_id,
'Sala_id'=>$AlunoDetalhes->Sala_id,
'Anolectivo_id'=>$AlunoDetalhes->Anolectivo_id,
])
->select('id')
->first();



$PagamentoMensal = Pagamento::PagamentoMensal($AlunoDetalhes->Anolectivo_id, $AlunoDetalhes->Classe_id,$mensalidadeDetalhesEstudante->id);



return  $PagamentoMensal;
 
}



}












 
 