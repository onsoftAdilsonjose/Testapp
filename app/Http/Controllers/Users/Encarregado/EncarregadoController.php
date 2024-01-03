<?php

namespace App\Http\Controllers\Users\Encarregado;


use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\DisciplinaParaClasse;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Notas;
use App\MyCustomFuctions\MinhasFuncoes;
use App\Helpers\Enacarregado;
use App\Helpers\Trimestre;
use App\MyCustomFuctions\Pagamento;
use App\Estudante\EstudanteInfounico;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EncarregadoController extends Controller
{
    //



public function Encarregado_me(){
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
'users.id as studentID',
'users.encarregadoID'
)
->where(['users.encarregadoID'=>$userId])
->get();

return $Estudante;
}




public function Encarregado_vernotas($studentID,$anolectivoID,$classeID){

$userId = Auth::id();


$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($classeID,$anolectivoID,$studentID);

$classes = DB::table('classes')
->join('mensalidade', 'mensalidade.Classe_id','=','classes.id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('tipodecurso', 'tipodecurso.id', '=', 'curso.tipodecursoID')
->where(['classes.id'=> $classeID, 'mensalidade.id'=>$SingleStudentDetalhes])
->select('classes.id','classes.ClassComExam','curso.tipodecursoID','curso.id as Cursoid')->first();






$Disciplina = Disciplina::join('livrode_notas', 'livrode_notas.disciplinaID', '=', 'disciplinas.id')
    ->join('users', 'users.id', '=', 'livrode_notas.studentID')
    ->join('periodos', 'periodos.id', '=', 'livrode_notas.periodoID')
    ->join('turmas', 'turmas.id', '=', 'livrode_notas.turmaID')
    ->join('salas', 'salas.id', '=', 'livrode_notas.salaID')
    ->join('classes', 'classes.id', '=', 'livrode_notas.classeID')
    ->join('ano_lectivos', 'ano_lectivos.id', '=', 'livrode_notas.anolectivoID')
    ->where([ 
    	'livrode_notas.studentID' => $studentID,
        'livrode_notas.classeID' => $classeID,
        'livrode_notas.anolectivoID' => $anolectivoID,
        //'livrode_notas.disciplinaID' => $disciplinaID,
         'users.encarregadoID'=> $userId 
     
    ])
  ->select(
         DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS full_name"),
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
        'livrode_notas.disciplinaID',
        'users.id as studentID',
        'users.encarregadoID as Encarregado',
        'livrode_notas.disciplinaID'
   )
    ->selectRaw('(livrode_notas.Mac1 + livrode_notas.Npt1 + livrode_notas.Npp1) / 3 AS MediaPrimeiroTrimestre')
    ->selectRaw('(livrode_notas.Mac2 + livrode_notas.Npt2 + livrode_notas.Npp2) / 3 AS MediaSegundoTrimestre')
    ->selectRaw('(livrode_notas.Mac3 + livrode_notas.Npt3 + livrode_notas.Npp3) / 3 AS MediaTerceriroTrimestre');


if ($classes->ClassComExam == 1) {
    $Disciplina = ($classes->tipodecursoID == 2 && $classes->id == 13)
    ?$Disciplina 
    : $Disciplina->addSelect('livrode_notas.Exam');

}
   $Disciplina = $Disciplina->get();
     


          return response()->json([
            'Encarregado_vernotas' => $Disciplina,
            // 'Disciplina' =>$Disciplina,
            // '$TrimestreCount'=>$TrimestreCount
        ]);



}




public function Encarregado_verpropinas(){

$studentID= 254;
$anolectivoID= 1;
$classeID= 11;






$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($classeID,$anolectivoID,$studentID);
$months = Pagamento::Months($classeID,$SingleStudentDetalhes);
$MesComDivida = Pagamento::MesesComDivida($studentID, $anolectivoID, $classeID,$SingleStudentDetalhes); 
$MesesPago = Pagamento::MesesPago($studentID, $anolectivoID,$classeID);
$PagarApartir = Pagamento::PagarApartir($studentID, $anolectivoID, $classeID);


$PagamentoMensal = Pagamento::PagamentoMensal($anolectivoID,$classeID,$SingleStudentDetalhes);
$CountMesComDivida = count($MesComDivida);
$Divida =round($CountMesComDivida * $PagamentoMensal,2);


$resultArray = MinhasFuncoes::checkMonths($months, $anolectivoID, $studentID, $classeID,$SingleStudentDetalhes);
$MesesComMultas = round($resultArray['totalCount'],2);
$mesesIDComMulta = $resultArray['MesesComMultas'];
$ValorDaMulta = MinhasFuncoes::calcularjuros($MesesComMultas * $PagamentoMensal);







$PagamentoMensal = round($PagamentoMensal,2);
$mesesIDComMulta = $mesesIDComMulta;
$MesesComMultas = $MesesComMultas;
$ValorDaMulta = round($ValorDaMulta,2);
$TotalMulta = round($ValorDaMulta*$MesesComMultas,2);







return response()->json([
	'MesesPago' =>$MesesPago,
	'MesComDivida'=>$MesComDivida,
	'Divida'=>$Divida,
	'PagamentoMensal'=>round($PagamentoMensal,2),
	'MesesComMultas'=>$MesesComMultas,
	'mesesIDComMulta'=>$mesesIDComMulta,
	'ValorDaMulta'=>$ValorDaMulta,
	'TotalMulta'=>$TotalMulta,






	'status' =>200,


],200);


}







/**
 *filtro de Ano Lectivo Para Estudante Logado.
 *
 * @OA\Get (
 *     path="/api/Encarregado/estudantefilter",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de pagamento do estudante para o ano letivo especificado",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *                 @OA\Property(property="anolectivoid", type="integer",example=1),
 *                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
 *                 @OA\Property(property="classeid",type="integer",example=1),
 *             )
 *         )
 *     )
 * )
 */



public function EncarregadofilhosFilter($AnoLectivo){

//filrar todosos filhos do encarregaod    
$filhos = Enacarregado::EnacarregadoFilhos($AnoLectivo);
return response()->json(['filhos' =>$filhos,],200);






}


/**
 *filtro de Ano Lectivo com filho do encarregado loggado.
 *
 * @OA\Get (
 *     path="/api/Encarregado/filhosAnolectivoFilter/Estudante",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
 *         name="studentID",
 *         in="path",
 *         required=true,
 *         description="studentID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de pagamento do estudante para o ano letivo especificado",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *                 @OA\Property(property="anolectivoid", type="integer",example=1),
 *                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
 *                 @OA\Property(property="classeid",type="integer",example=1),
 *             )
 *         )
 *     )
 * )
 */



public function EncarregadoFilhosAnolectivoFilter(){
//filrar todosos anolectivo filho selecionado

$anolectivofilhos = Enacarregado::FilhosAnolectivo();
return response()->json(['anolectivofilhos' =>$anolectivofilhos,],200);
}









    
}
