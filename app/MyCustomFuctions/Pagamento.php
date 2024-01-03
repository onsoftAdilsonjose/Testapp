<?php

namespace App\MyCustomFuctions;

use NumberToWords\NumberToWords;
use DB;
use App\Models\Transactions;
use App\Models\Meses;
use App\Models\classes;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Role;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Http\JsonResponse;
use PDF;
use App\Mail\SendPDFEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\EstudanteSaldo;
use App\MyCustomFuctions\Customised;

class Pagamento
{
   


public static function Saldo($studentID) {
    $Saldo = EstudanteSaldo::where(['student_id' => $studentID])->first();

    if ($Saldo) {
        return $Saldo->saldo_amount;
    } else {
        return 0;
    }
    
}


public static function Estudante_x_Ano_x_Classes (){





        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            // ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['users.status' => 0])
            ->select(
                'estudante_x_ano_x_classe.id',
                'curso.nomeCurso',
                'classes.classe_name',
                'salas.nomeSala',
                'turmas.nomeTurma',
                'periodos.nomePeriodo',
                'ano_lectivos.ano_lectivo',
                // 'mensalidade.ConfirmacaoPreco',
                // 'mensalidade.MatriculaPreco',
                // 'mensalidade.Propina_Anual',
                'users.primeiro_nome',
                'users.ultimo_nome',
                'users.reg_Numero',
                'classes.ClassComExam',
                'ano_lectivos.id as anolectivoID',
                'classes.id as classeID',
                'users.id as studentID'
            )->get();





 return $Estudante_x_Ano_x_Classe;





}

public static function Estudante_x_Ano_x_Classe ($studentID, $anolectivoID, $classeId,$SingleStudentDetalhes){

               $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->where(['users.status' => 0, 'estudante_x_ano_x_classe.Classe_id' => $classeId, 'ano_lectivos.id' => $anolectivoID, 'users.id' => $studentID,'mensalidade.id'=>$SingleStudentDetalhes])
            ->select(
                'estudante_x_ano_x_classe.id',
                'curso.nomeCurso',
                'classes.classe_name',
                'salas.nomeSala',
                'turmas.nomeTurma',
                'periodos.nomePeriodo',
                'ano_lectivos.ano_lectivo',
                'users.primeiro_nome',
                'users.ultimo_nome',
                'users.reg_Numero',
                'mensalidade.ConfirmacaoPreco',
                'mensalidade.MatriculaPreco',
                'mensalidade.Propina_Anual',
                'classes.ClassComExam',
                'ano_lectivos.id as anolectivoID',
                'classes.id as classeID',
                'users.id as studentID'
            )->first();




            



            $estadodepagamento = Customised::ConfirmacaoMatriculaPago($studentID, $anolectivoID, $classeId);
            $Estudante_x_Ano_x_Classe->estadodepagamento = $estadodepagamento;






return $Estudante_x_Ano_x_Classe;


}












public static function MesesComDivida ($studentID, $anolectivoID, $classeId,$SingleStudentDetalhes){

$classes = DB::table('classes')
->join('mensalidade', 'mensalidade.Classe_id','=','classes.id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('tipodecurso', 'tipodecurso.id', '=', 'curso.tipodecursoID')
->where(['classes.id'=> $classeId, 'mensalidade.id'=>$SingleStudentDetalhes])
->select('classes.id','classes.ClassComExam','curso.tipodecursoID','curso.id as Cursoid')->first();


$query = DB::table('meses')
    ->leftJoin('transactions', function ($join) use ($studentID, $anolectivoID, $classeId) {
        $join->on('meses.mesID', '=', 'transactions.MesesID')
            ->where('transactions.studentID', '=', $studentID)
            ->where('transactions.anolectivoID', '=', $anolectivoID)
            ->where('transactions.Cancelar', '=', 0)
            ->where('transactions.classID', '=', $classeId);
    })
    ->whereNull('transactions.id')
    ->select('meses.mesNome', 'meses.mesID', 'meses.mesAnolectivoID', 'meses.created_at')
    ->orderBy('meses.orderNumber', 'ASC');

if ($classes->ClassComExam == 1) {

     
($classes->tipodecursoID == 2 && $classes->id == 13 ) ? $query->whereIn('meses.ClassComExam', [0, 1]): $query->where('meses.ClassComExam', 0);



 } else {
    $query->where('meses.ClassComExam', 0);
}

$MescomDivida = $query->get();

return $MescomDivida;


}

   


public static function CountMeses ($anolectivoID, $classeId,$SingleStudentDetalhes){

$classes = DB::table('classes')
->join('mensalidade', 'mensalidade.Classe_id','=','classes.id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('tipodecurso', 'tipodecurso.id', '=', 'curso.tipodecursoID')
->where(['classes.id'=> $classeId, 'mensalidade.id'=>$SingleStudentDetalhes])
->select('classes.id','classes.ClassComExam','curso.tipodecursoID','curso.id as Cursoid')->first();
$query = Meses::where('mesAnolectivoID', $anolectivoID);

if ($classes->ClassComExam ==1) {
 ($classes->tipodecursoID == 2 && $classes->id == 13 ) ? $query->whereIn('meses.ClassComExam', [0, 1]): $query->where('meses.ClassComExam', 0);

} else {
    $query->where('meses.ClassComExam', 0);
}

$countMeses = $query->count();

return $countMeses;

}







// public static function PaymentOrder (){
//             $paymentOrder = IdGenerator::generate([
//                 'table' => 'payments',
//                 'field' => 'paymentOrder',
//                 'length' => 10,
//                 'prefix' => 'FT' . '-' . date('Ymi'),
//             ]);
// return $paymentOrder;

// }




public static function PaymentOrdercancel()
{
    $generatedId = IdGenerator::generate([
        'table' => 'payments', 
        'field' => 'paymentOrder',
        'length' => 10, 
        'prefix' => 'NC S' . date('Y') . '/',
    ]);

    return $generatedId;
}




public static function PaymentOrder()
{
    $generatedId = IdGenerator::generate([
        'table' => 'payments', 
        'field' => 'paymentOrder',
        'length' => 10, 
        'prefix' => 'FT S' . date('Y') . '/',
    ]);

    return $generatedId;
}

















public static function  Months ($classeId,$SingleStudentDetalhes){
$classes = DB::table('classes')
->join('mensalidade', 'mensalidade.Classe_id','=','classes.id')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('tipodecurso', 'tipodecurso.id', '=', 'curso.tipodecursoID')
->where(['classes.id'=> $classeId, 'mensalidade.id'=>$SingleStudentDetalhes])
->select('classes.id','classes.ClassComExam','curso.tipodecursoID','curso.id as Cursoid')->first();
$query = Meses::select('mesID');

if ($classes->ClassComExam == 1) {
($classes->tipodecursoID == 2 && $classes->id == 13 ) ? $query->whereIn('meses.ClassComExam', [0, 1]): $query->where('meses.ClassComExam', 0);

} else {
    $query->where('meses.ClassComExam', 0);
}

$months = $query->get()->toArray();


return $months;

}









public static function  PagarApartir ($studentID, $anolectivoID, $classeId){
 
        $PagarApartir = DB::table('meses')
            ->leftJoin('transactions', function ($join) use ($studentID, $anolectivoID, $classeId) {
                $join->on('meses.mesID', '=', 'transactions.MesesID')
                    ->where('transactions.studentID', '=', $studentID)
                    ->where('transactions.anolectivoID', '=', $anolectivoID)
                    ->where('transactions.Cancelar', '=', 0)
                    ->where('transactions.classID', '=', $classeId);
            })
            ->whereNull('transactions.id')

            ->select('meses.mesNome', 'meses.mesID', 'meses.mesAnolectivoID', 'meses.created_at','meses.orderNumber')
            //->orderByRaw("MONTH(inicio) ASC")
            ->orderBy('meses.orderNumber', 'ASC')
            ->first();
            
return $PagarApartir;

}





public static function  MesesPago ($studentID, $anolectivoID, $classeId){
 

       $MesesPago = DB::table('transactions')
            ->join('meses', 'meses.mesID', '=', 'transactions.MesesID')
            ->where('studentID', '=', $studentID)
            ->where('anolectivoID', '=', $anolectivoID)
            ->where('classID', '=', $classeId)
            ->select('meses.mesNome', 'meses.mesID', 'meses.mesAnolectivoID')
            ->where('transactions.Cancelar', '=', 0)
            ->get();

            return $MesesPago;

}



public static function  PagamentoMensal ($anolectivoID, $classeId,$SingleStudentDetalhes){
 
       $countMeses = Pagamento:: CountMeses($anolectivoID,$classeId,$SingleStudentDetalhes);
       
       $PagamentoMensal= DB::table('mensalidade')
            ->where('Anolectivo_id', '=', $anolectivoID)
            ->where(['Classe_id'=> $classeId, 'mensalidade.id'=>$SingleStudentDetalhes])
            ->select('Propina_Anual')
            ->first();

        return $PagamentoMensal->Propina_Anual/$countMeses;

}





public static function VerificarMeses($CheckMeses, $studentID, $anolectivoID, $classeID)
{
    foreach ($CheckMeses as $item) {
        $existingRecord = Transactions::where([
            'MesesID' => $item['mesID'],
            'anolectivoID' => $item['mesAnolectivoID'],
            'studentID' => $studentID,
            'classID' => $classeID,
            'Cancelar' =>0,
        ])->first();

        if ($existingRecord) {
            // A matching record already exists, return false
            return false;
        }
    }

    // No matching records found, return true
    return true;
}




 











public static function  QuantoPorCentoFoidescontado($preco , $descount)
{

  $percentage = ($descount / $preco) * 100;
 
return $percentage;

}





 








public static function Estudante($Id) {

   $months = User::select('users.id')
        ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.student_id', '=', 'users.id')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id') 
        ->where('estudante_x_ano_x_classe.Anolectivo_id', '=', $Id)->get()->toArray();

    return $months;
}





public static function  SingleStudentDetalhes($classeId,$anolectivoID,$studentID) {

 

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


    return $mensalidadeDetalhesEstudante->id;
}


 
public static function  pagamentoPorSaldo($studentID,$saldoAserPago) {
    

$estudentSaldo = Pagamento::Saldo($studentID);

$estudentSaldo = floatval($estudentSaldo);
 $saldoAserPago = floatval($saldoAserPago);



$updatestudentSaldo = EstudanteSaldo::where('student_id',$studentID)
->update(['saldo_amount' => $estudentSaldo - $saldoAserPago]);
   return $saldoAserPago ;

}






public static function  saldoAserGuardado($studentID,$saldoAserGuardado) {
    

$estudentSaldo = Pagamento::Saldo($studentID);

$estudentSaldo = floatval($estudentSaldo);
$saldoAserGuardado = floatval($saldoAserGuardado);



$updatestudentSaldo = EstudanteSaldo::where('student_id',$studentID)
->update(['saldo_amount' => $estudentSaldo + $saldoAserGuardado]);
   return $saldoAserGuardado ;

}











 }