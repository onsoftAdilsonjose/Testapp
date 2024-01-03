<?php

namespace App\Http\Controllers\Users\Estudante;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Estudante_x_Ano_x_Classe;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EstudanteController extends Controller
{
    //







public function Estudante_me(){
$userId = Auth::id();




$Estudante = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
->where(['users.status' => 0])
->select(
// 'estudante_x_ano_x_classe.id',
'curso.nomeCurso',
'classes.classe_name',
'salas.nomeSala',
'turmas.nomeTurma',
'periodos.nomePeriodo',
'ano_lectivos.ano_lectivo',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
DB::raw("YEAR(NOW()) - YEAR(dataofbirth) - IF(DATE_FORMAT(NOW(), '%m-%d') < DATE_FORMAT(dataofbirth, '%m-%d'), 1, 0) AS idade"),
'users.reg_Numero',
'classes.ClassComExam',
'ano_lectivos.id as anolectivoID',
'classes.id as classeID',
'users.id as studentID'
)
->where(['users.id'=>$userId])
->get();


if ($Estudante->isEmpty()) {
return response()->json(['message' => 'Classes não encontrada','status'=>404], 404);
}



 
foreach ($Estudante as $Estudantes) {
$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($Estudantes->classeID,$Estudantes->anolectivoID,$userId);
$months = Pagamento::Months($Estudantes->classeID,$SingleStudentDetalhes);
$MesComDivida = Pagamento::MesesComDivida($userId, $Estudantes->anolectivoID, $Estudantes->classeID,$SingleStudentDetalhes); 
$MesesPago = Pagamento::MesesPago($userId, $Estudantes->anolectivoID, $Estudantes->classeID);
$PagarApartir = Pagamento::PagarApartir($userId, $Estudantes->anolectivoID, $Estudantes->classeID);


$PagamentoMensal = Pagamento::PagamentoMensal($Estudantes->anolectivoID,$Estudantes->classeID,$SingleStudentDetalhes);
$CountMesComDivida = count($MesComDivida);
$Divida =$CountMesComDivida * $PagamentoMensal;


$resultArray = MinhasFuncoes::checkMonths($months, $Estudantes->anolectivoID, $userId, $Estudantes->classeID,$SingleStudentDetalhes);
$MesesComMultas = round($resultArray['totalCount'],2);
$mesesIDComMulta = $resultArray['MesesComMultas'];
$ValorDaMulta = MinhasFuncoes::calcularjuros($MesesComMultas * $PagamentoMensal);


$Estudantes->MesesPago = $MesesPago;
$Estudantes->MesComDivida = $MesComDivida;
$Estudantes->PagarApartir = $PagarApartir;
// $Estudantes->Divida = round($Divida,2);
$Estudantes->PagamentoMensal = round($PagamentoMensal,2);
$Estudantes->mesesIDComMulta = $mesesIDComMulta;
$Estudantes->MesesComMultas = $MesesComMultas;
$Estudantes->ValorDaMulta = round($ValorDaMulta,2);
$Estudantes->TotalMulta = round($ValorDaMulta*$MesesComMultas,2);
}






return response()->json([
	'estudanteMe' =>$Estudante,
    'Divida'=>$Divida,
	'status' =>200,


],200);


}

public function Estudante_notas($classeID,$anolectivoID){
$userId = Auth::id();






$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($classeID,$anolectivoID,$userId,);
$classes = DB::table('classes')
->join('mensalidade', 'mensalidade.Classe_id','=','classes.id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('tipodecurso', 'tipodecurso.id', '=', 'curso.tipodecursoID')
->where(['classes.id'=>$classeID, 'mensalidade.id'=>$SingleStudentDetalhes,'mensalidade.Anolectivo_id'=>$anolectivoID])
->select('classes.id','classes.ClassComExam','curso.tipodecursoID','curso.id as Cursoid')->first();


$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([
        'livrode_notas.classeID' => $classeID,
        'livrode_notas.anolectivoID' => $anolectivoID,
        'livrode_notas.studentID' => $userId,
        // 'users.usertype','=','Estudante' 
    ])
    ->select(
        'disciplinas.nomeDisciplina',
        'livrode_notas.Mac1',
        'livrode_notas.Npt1',
        'livrode_notas.Npp1',
        'livrode_notas.Mac2',
        'livrode_notas.Npt2',
        'livrode_notas.Npp2',
        'livrode_notas.Mac3',
        'livrode_notas.Npt3',
        'livrode_notas.Npp3',
        'livrode_notas.disciplinaID'
    )
->selectRaw('ROUND((livrode_notas.Mac1 + livrode_notas.Npt1 + livrode_notas.Npp1) / 3, 2) AS MediaPrimeiroTrimestre')
->selectRaw('ROUND((livrode_notas.Mac2 + livrode_notas.Npt2 + livrode_notas.Npp2) / 3, 2) AS MediaSegundoTrimestre')
->selectRaw('ROUND((livrode_notas.Mac3 + livrode_notas.Npt3 + livrode_notas.Npp3) / 3, 2) AS MediaTerceiroTrimestre');


if ($classes->ClassComExam == 1) {
	$Disciplina = ($classes->tipodecursoID == 2 && $classes->id == 13)
	?$Disciplina 
	: $Disciplina->addSelect('livrode_notas.Exam');

}
    $Disciplina = $Disciplina->get();







return $Disciplina;

}


}




 