<?php

namespace App\Estudante;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Disciplina;
use App\Models\Notas;
use App\Models\AnoLectivo;
use App\Models\Meses;


class EstudanteInfounico
{
   





 
//aqui onde nos vamos encotra estudante com classe atravez do ano lectivo
public static function getstudentInfo($anolectivo,$tudentid){

$detalhes= Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')

->where([
'users.id' => $tudentid,
'Anolectivo_id'=>$anolectivo
])
->select(   
        'student_id',
        'Periodo_id' ,
        'Turma_id' ,
        'Sala_id',
        'Classe_id',
        'Curso_id',
        'Anolectivo_id')
->first();



return $detalhes;


}






  public static function relatoriodePagamentodoaluno($anolectivoID){
$userId = Auth::id();
$relatoriodePagamento = DB::table('payments')
->join('tipodepagamento', 'tipodepagamento.id', '=', 'payments.TipodePagementoID')
->select(
'ValorPago',
'Tipodepagamento',
'paymentOrder',
'Descount',
'Cancelar as Status',
'SaldoGuardado',
'SaldoRemovido',

)
->where(['payments.studentID'=>$userId, 'payments.anolectivoID'=>$anolectivoID])
->get();

return response()->json([
'relatoriodePagamento' => $relatoriodePagamento

]);


}






public static function ApagarNotasAntiga($studentID, $classeID, $anolectivoID)
{
    // Query to retrieve records from the 'livrode_notas' table
    $notas = DB::table('livrode_notas')
        ->where(['classeID' => $classeID, 'studentID' => $studentID, 'anolectivoID' => $anolectivoID,])
        ->select('id')
        ->get();

    // Check if there are any records returned
    if ($notas) {
        // Loop through each record
        foreach ($notas as $nota) {
            // Find the corresponding record in the 'Notas' model using the 'id' field
            $deletedisciplina = Notas::find($nota->id);

            // Delete the record
            $deletedisciplina->delete();
        }
    }
}





public static function RegNumber($RegNumber){

$Reg= DB::table('users')
->Leftjoin('pessoa','.pessoa.id','=','users.pessoa_id') // commented out as it's not being used
->where(['users.reg_Numero'=>$RegNumber])->select(
'users.id as id','encarregadoID',
DB::raw("CONCAT(ultimo_nome, ' ', primeiro_nome) as nomeCompleto"),
'numeroDotelefone',
'email',
'users.reg_Numero',
'genero_id','pais','dataofbirth','numeroDoDocumento'
)->first();

 if ($Reg) {
  return $Reg;
 }
 

}



public static function EncarregadoInfo($encarregadoid){

$Reg= DB::table('users')->where(['id'=>$encarregadoid])->select(
	DB::raw("CONCAT(ultimo_nome, ' ', primeiro_nome) as nomeCompleto"),
	'numeroDotelefone','email'
)->first();
 if ($Reg) {
  return $Reg;
 }
 

}





public static function EsteAlunojamatriculado($estudante,$anoLectivo){
$existingRecord = Estudante_x_Ano_x_Classe::where(['student_id'=>$estudante,'Anolectivo_id'=>$anoLectivo])
->first();
if ($existingRecord) {
return response()->json(['errors' => 'Este Aluno já está matriculado no corrente ano letivo'], 422);
}
}






}


 