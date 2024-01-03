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

class AreiaAcademicaController extends Controller
{
    //




/**
 * ver lista de Notas de todos os trimestres e disciplinas
 *
 * @OA\Get (
 *     path="/api/Encarregado/vernotas/Estudante/{studentID}/AnoLectivo/{anolectivoID",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
 *         name="studentID",
 *         in="path",
 *         required=true,
 *         description="studentID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista da notas do ano lectivo escolhido",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="Mac1", type="number",example="17"),
*                 @OA\Property(property="Npt1", type="number",example="12"),
*                 @OA\Property(property="Npp1", type="number",example="11"),
*                 @OA\Property(property="Mac2", type="number",example="17"),
*                 @OA\Property(property="Npt2", type="number",example="12"),
*                 @OA\Property(property="Npp2", type="number",example="11"),
*                 @OA\Property(property="Mac3", type="number",example="17"),
*                 @OA\Property(property="Npt3", type="number",example="12"),
*                 @OA\Property(property="Npp3", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *                 @OA\Property(property="MediaSegundoTrimestre", type="string",example="12"),
 *                 @OA\Property(property="MediaTerceriroTrimestre", type="integer",example="18"),

 *             )
 *         )
 *     )
 * )
 */



public function Encarregado_vernotas ($studentID,$anolectivoID){



       //$tudentid = Auth::id();
         


		$classeId = EstudanteInfounico::getstudentInfo($anolectivoID,$studentID);
		$encarregadovernotas = Enacarregado::EncarregadoVerTodasNotas($anolectivoID,$classeId->Classe_id,$studentID);

          return response()->json([
           $encarregadovernotas 
        ]);





}





/**
 * lista de Historico de Todas Notas e Disciplinas 
 *
 * @OA\Get (
 *     path="/api/Encarregado/vernotas/Estudante/{studentID}/historico",
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
 *         description="Lista da notas do ano lectivo escolhido",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
*                 @OA\Property(property="Mac1", type="number",example="17"),
*                 @OA\Property(property="Npt1", type="number",example="12"),
*                 @OA\Property(property="Npp1", type="number",example="11"),
*                 @OA\Property(property="Mac2", type="number",example="17"),
*                 @OA\Property(property="Npt2", type="number",example="12"),
*                 @OA\Property(property="Npp2", type="number",example="11"),
*                 @OA\Property(property="Mac3", type="number",example="17"),
*                 @OA\Property(property="Npt3", type="number",example="12"),
*                 @OA\Property(property="Npp3", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *                 @OA\Property(property="MediaSegundoTrimestre", type="string",example="12"),
 *                 @OA\Property(property="MediaTerceriroTrimestre", type="string",example="18"),

 *             )
 *         )
 *     )
 * )
 */
          
public function Encarregado_vernotas_historico ($studentID){


  
        $vernotas_historico =  Enacarregado::notasencarregadohistorico($studentID);
          return response()->json([
         $vernotas_historico
        ]);

     }





/**
 * lista de Boletim de um Determinado Trimestre 
 *
 * @OA\Get (
 *     path="/api/Encarregado/boletimestral/Estudante/{studentID}/Anolectivo/{Anolectivo}/trimestre/{trimestre}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
  *     @OA\Parameter(
 *         name="studentID",
 *         in="path",
 *         required=true,
 *         description="studentID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="trimestre",
 *         in="path",
 *         required=true,
 *         description="ID do trimestre",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Boletim de Notas do Trimestre Escolhido",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
*                 @OA\Property(property="Mac1", type="number",example="17"),
*                 @OA\Property(property="Npt1", type="number",example="12"),
*                 @OA\Property(property="Npp1", type="number",example="11"),
 *                 @OA\Property(property="MediaPrimeiroTrimestre", type="string",example="16"),
 *             )
 *         )
 *     )
 * )
 */

public function BoletimEncarregado($tudentid,$Anolectivo,$trimestre){

$estudante = EstudanteInfounico::getstudentInfo($Anolectivo,$tudentid);

$Boletim = Enacarregado::BoletimEnacrregado($trimestre,$Anolectivo,$tudentid,$estudante->Classe_id);
return response()->json(['Boletim'=>$Boletim],200);

}




/**
 * lista de Disciplina Determinado Ano Lectivo de Um Estudante
 *
 * @OA\Get (
 *     path="/api/Encarregado/disciplinas/Estudante/{studentID}/anolectivo/{ano}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="studentID",
 *         in="path",
 *         required=true,
 *         description="studentID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de Disciplinas de um determiando Ano Lectivo",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *               @OA\Property(property="Professor Professor", type="string",example="Joao Miguel"),
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *             )
 *         )
 *     )
 * )
 */






 public function EstudaTodasDisciplinaEncarregado($tudentid,$anolectivo){

$userId = Auth::id();
$disciplinaporanolectivo = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where(['estudante_x_ano_x_classe.student_id'=> $tudentid,'estudante_x_ano_x_classe.Anolectivo_id'=> $anolectivo])
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo',
 DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) AS professor"))
->get();

return response()->json(['disciplinaporanolectivo'=>$disciplinaporanolectivo],200);

}









/**
 * lista de Grade curricular Determinado 
 *
 * @OA\Get (
 *     path="/api/Encarregado/disciplinas/gradecurricular/Estudante/{studentID}",
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
 *         description="Lista de Grade curricular",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="Professor Professor", type="string",example="Joao Miguel"),
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\2024"),
 *             )
 *         )
 *     )
 * )
 */




public function EncarregadoGradecurricular($userId){

//$userId = Auth::id();

$Gradecurricular = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where('estudante_x_ano_x_classe.student_id', $userId)
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo')->get();

return response()->json(['Gradecurricular'=>$Gradecurricular],200);
}









/**
 * lista de Disciplinas Pendentes or para Recurso
 *
 * @OA\Get (
 *     path="/api/Encarregado/disciplinas/pendentes/Estudante/{studentID}/anolectivo/{ano}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="studentID",
 *         in="path",
 *         required=true,
 *         description="studentID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
  *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="lista de Disciplinas Pendentes or para Recurso",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="Professor Professor", type="string",example="Joao Miguel"),
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *             )
 *         )
 *     )
 * )
 */

public function EncarregadoDisciplinaspendentes($tudentid,$anolectivo){

//$userId = Auth::id();

$Disciplinaspendentes = DisciplinaParaClasse::join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Anolectivo_id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
->where('estudante_x_ano_x_classe.student_id', $tudentid)
->where('estudante_x_ano_x_classe.Anolectivo_id',$anolectivo)
->select('nomeDisciplina', 'curso.nomeCurso', 'classes.classe_name', 'ano_lectivos.ano_lectivo')->get();
return response()->json(['Disciplinaspendentes '=>$Disciplinaspendentes],200);
}






/**
 * Consultar Saldo
 *
 * @OA\Get (
 *     path="/api/Encarregado/consultarsaldo/Estudante/{studentID}",
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
 *         description="Consultar saldo do estudante",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="saldo", type="number",example="10.000"),
 *             )
 *         )
 *     )
 * )
 */
public function EncarregadoConsultarSaldo($userid){
// $userId = Auth::id();
$Saldo = Pagamento::Saldo($userid);
return response()->json([
'saldo'=>$Saldo
]);

}










public function encarregadoExtratoFinaceiro($tudentid,$anolectivo){



return 'esta rota';



}






 
/**
 * Detalhes de De Todos Os Pagamentos que foram Feito Anualmente
 * @OA\Get (
 *     path="/api/Encarregado/ConsultarPagamento/anolectivo/{anolectivo}/estudante/{id}",
 *     tags={"Encarregado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="ano",
 *         in="path",
 *         required=true,
 *         description="ID do ano letivo",
 *         @OA\Schema(type="integer")
 *     ),
  *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do estudante",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalhes de Consulta de Todos os Pagamnetos Feitos",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
  *               @OA\Property(property="Professor Professor", type="string",example="Joao Miguel"),
 *                @OA\Property(property="nomeCurso", type="string",example="Ciências Físicas e Biológica"),
*                 @OA\Property(property="nomeDisciplina", type="string",example="INGLÊS"),
*                 @OA\Property(property="nomePeriodo",type="string",example="Tarde"),
*                 @OA\Property(property="nomeSala", type="string",example="Sala 9"),
*                 @OA\Property(property="nomeTurma", type="string",example="B"),
*                 @OA\Property(property="classe_name", type="string",example="10 Classe"),
*                 @OA\Property(property="ano_lectivo", type="string",example="2023\/2024"),
 *             )
 *         )
 *     )
 * )
 */


public function encaregadoConsultarPagamento($anolectivo,$esudanteid){

$userId = Auth::id();
$relatorioDepropina = DB::table('transactions')
->join('meses', 'meses.id', '=', 'transactions.MesesID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'transactions.anolectivoID')
->join('payments', 'payments.id', '=', 'transactions.payment_id')
->join('users', 'users.id', '=', 'transactions.studentID')
->select(
'transactions.paymentOrder',
'ano_lectivo',
'mesNome',
'transactions.Multa',
'transactions.Preco',
'transactions.Descount',
'transactions.anolectivoID',
'transactions.studentID',
//'payments.id as paymentid'
)
->where([
'transactions.studentID'=>$esudanteid,
'transactions.anolectivoID'=>$anolectivo,
'encarregadoID'=>$userId,
'transactions.Cancelar'=>0,
'payments.Cancelar'=>0,
])
->orderBy('orderNumber', 'ASC') // Add this line to order by orderNumber
->get();

$collection = collect($relatorioDepropina);
$propinas = $collection->groupBy('paymentOrder');
$propinas->all();


$relatorioDeservico = DB::table('transatiosservico')
->join('servicos', 'servicos.id', '=', 'transatiosservico.servicoID')
->join('payments', 'payments.id', '=', 'transatiosservico.payment_id')
->join('users', 'users.id', '=', 'transatiosservico.studentID')
->select(
'payments.paymentOrder',
'transatiosservico.Quantidade',
'transatiosservico.Preco',
'servicos.ServicoNome',
'transatiosservico.Descount',
'transatiosservico.payment_id',
'servicoID',
//DB::raw('CASE WHEN Cancelar = 1 THEN 0 ELSE ROUND((transatiosservico.Preco * transatiosservico.Quantidade - Descount), 2) END as servicoTotal')
)
->where([
'transatiosservico.studentID'=>$esudanteid,
'transatiosservico.anolectivoID'=>$anolectivo,
'encarregadoID'=>$userId,
'payments.Cancelar'=>0,
])
->get();

$collection1 = collect($relatorioDeservico);
$servicos = $collection1->groupBy('paymentOrder');
$servicos->all();

return response()->json([
'propinas'=>$propinas,
'servicos'=>$servicos
]);

}










}
