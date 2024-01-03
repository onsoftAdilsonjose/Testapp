<?php

namespace App\MyCustomFuctions;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;


class Customised
{
   

public static function Genero($Id) {
    $genero = DB::table('genero')->where(['id' => $Id])->select('generoNome', 'id')->first();
    if ($Id !== null && $genero !== null) {
     return $genero->generoNome;
    }
   return 'Uknown';
}



public static function Paises($Id){
$paises = DB::table('paises')->where(['id' => $Id])->select('Nome', 'id')->first();
if ($Id !== null && $paises !== null) {
return $paises->Nome;
}
return 'Uknown';
}






public static function Provincia($Id){
     $provincias = DB::table('provincias')->where(['id'=> $id])->select('Nome','id')->first();
    if ($Id !== null && $provincias !== null) {
    return $provincias->Nome;
    }

    return 'Uknown';

}



public static function Municipio($Id){
$municipios = DB::table('municipios')->where(['id'=> $id])->select('Nome','id')->first();
if($Id !== null && $municipios !== null ){
return $municipios->Nome;    
}
 return 'Uknown';

}










public static function dadosPessoais($estudanteid){

$Reg= DB::table('users')
->Leftjoin('pessoa','.pessoa.id','=','users.pessoa_id') // commented out as it's not being used
->where(['users.reg_Numero'=>$estudanteid])->select(
'users.id','encarregadoID',
DB::raw("CONCAT(ultimo_nome, ' ', primeiro_nome) as nomeCompleto"),
'numeroDotelefone',
'email',
'users.reg_Numero',
'genero_id','pais','dataofbirth','numeroDoDocumento'
)->first();
 
return $Reg;

}


public static function dadosEncarregado($encarregadoid){
///Dados Encarregado   Mome do pai  Completo | Outro Encaregado | Telefone | Email|Nacionalidade



}





public static function dadosCademico ($peridoId,$classeId,$cursoId,$salaId,$turmaId,$ano_lectivos){
//Dados a Cademico  Ano Lecticvo Curso Classe Sala Periodo Numero do Processo  ? Ano Lectivo

$dadosCademico= DB::table('mensalidade')
->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
->where([
'mensalidade.Periodo_id'=>$peridoId,
'mensalidade.Anolectivo_id'=>$ano_lectivos,
'mensalidade.Classe_id'=>$classeId,
'mensalidade.Curso_id'=>$cursoId,
'mensalidade.Sala_id'=>$salaId,
'mensalidade.Turma_id'=>$turmaId])
->select(
//'mensalidade.Propina_Anual',
'mensalidade.MatriculaPreco',
'mensalidade.ConfirmacaoPreco',
'classe_name',
'nomeCurso',
'nomePeriodo',
'ano_lectivo',
'nomeSala',
'nomeTurma',
'ano_lectivo',
)
->first();



return $dadosCademico;







}




public static function get_student_info_for_Watsapp ($reg_numero){
//aqui estarao as informacao de  pagamento e informacao de notas que serao solicitadas vias watsapp




}
 
 


public static function confirmacaoMatriculaPago($studentID, $anolectivoID, $classeId)
{
    $estudanteDetalhesUnico = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
        ->join('mensalidade', 'mensalidade.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
        ->select('ConfirmacaoPreco', 'MatriculaPreco', 'users.id as student_id')
        ->where(['mensalidade.Classe_id' => $classeId, 'mensalidade.Anolectivo_id' => $anolectivoID, 'estudante_x_ano_x_classe.student_id' => $studentID])
        ->first();

    $alunoPagou = DB::table('registro')
        ->where(['student_id' => $studentID, 'Anolectivo_id' => $anolectivoID, 'Classe_id' => $classeId,'cancelar'=>0])
        ->exists();

    $contar = DB::table('registro')->where(['student_id' => $studentID,'cancelar'=>0])->count();

    $estado = $alunoPagou;

    if (!$estado) {
        $preco = $estudanteDetalhesUnico->MatriculaPreco;
        $servico = ($contar == 0 || is_null($contar)) ? 'Matricula' : 'Confirmacao';

        $result = [
            'Preco' => $preco,
            'Servico' => $servico,
            'Matriculapaga' => $estado,
        ];

        return $result;
    }

    return ['Matriculapaga' => $estado];
}





}


  

 


     
