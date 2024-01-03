<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\Validator;
use App\MyCustomFuctions\Pagamento;


class MensalidadeController extends Controller
{ 


public function mensalidades(){

    $mensalidades = Mensalidade::join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
        ->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
        ->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
        ->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
        ->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
        ->select(
            'mensalidade.Propina_Anual',
            'mensalidade.ConfirmacaoPreco',
            'mensalidade.MatriculaPreco',
            'nomeCurso',
            'nomePeriodo',
            'nomeTurma',
            'nomeSala',
            'classe_name',
            'ano_lectivo',
            'mensalidade.Classe_id',
            'mensalidade.Anolectivo_id',
            'mensalidade.Curso_id',
            'mensalidade.Sala_id',
            'mensalidade.Turma_id',
            'mensalidade.Periodo_id',
        )
        ->get();


    foreach ($mensalidades as $mensalidade) {
        //$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($mensalidade->Classe_id,$mensalidade->Anolectivo_id,$request->input('studentID'));
        //$PagamentoMensal = Pagamento::PagamentoMensal($mensalidade->Anolectivo_id, $mensalidade->Classe_id,$SingleStudentDetalhes);
        //$mensalidade->PagamentoMensal = $PagamentoMensal; // Add the Pagamento data to each Mensalidade
    }

    return response()->json(['mensalidades' => $mensalidades], 200);
}









public function storemensalidades(Request $request){
		$validator = Validator::make($request->all(), [
		    // Define your validation rules here
		    'Propina_Anual' => 'required|numeric|min:0',
		    'ConfirmacaoPreco' => 'required|numeric|min:0',
		    'MatriculaPreco' => 'required|numeric|min:0',
		    'Curso_id' => [
		        'required_if:Classe_id,!=0,1,2,3,4,5,6,7,8,9,10', // Curso_id is required if Classe_id is not one of the specified values
		        'integer',
		        'exists:curso,id',
		    ],
		    //'Anolectivo_id' => 'required|integer|exists:ano_lectivos,id',
		    'Periodo_id' => 'required|integer|exists:periodos,id',
		    'Sala_id' => 'required|integer|exists:salas,id',
		    'Turma_id' => 'required|integer|exists:turmas,id',
		    'Classe_id' => 'required|integer|exists:classes,id',
		]);

    if ($validator->fails()) {
        $firstError = $validator->errors()->first();
        return response()->json(['error' => $firstError], 422);
    }
        $CursoID =  $request->input('Classe_id') <= 10 ? 5 : $request->input('Curso_id');
       $anolectivo = DB::table('ano_lectivos')->select('id')->first();
        //// Ano Lectivo ID


		$existingRecord = Mensalidade::where('Classe_id',$request->Classe_id)
							
                             ->where('Anolectivo_id', $anolectivo->id)
							->where('Curso_id', $CursoID)
							->where('Turma_id',$request->Turma_id)
							->where('Sala_id',$request->Sala_id)
							//->where('Periodo_id',$request->Periodo_id)
		->first();
		if ($existingRecord) {
		return response()->json(['errors' => 'Os Dados Enviados São Duplicados'], 422);
		}

    DB::beginTransaction();

    try {


        $mensalidade = new Mensalidade();
        $mensalidade->Propina_Anual = $request->input('Propina_Anual');
        $mensalidade->Classe_id = $request->input('Classe_id');
        $mensalidade->Curso_id = $CursoID;
        $mensalidade->Anolectivo_id = $anolectivo->id;
        $mensalidade->ConfirmacaoPreco = $request->input('ConfirmacaoPreco');
        $mensalidade->MatriculaPreco = $request->input('MatriculaPreco');
        $mensalidade->Periodo_id = $request->input('Periodo_id');
        $mensalidade->Turma_id = $request->input('Turma_id');
        $mensalidade->Sala_id = $request->input('Sala_id');
        $mensalidade->save();

        
        $mensalidades = Mensalidade::join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'mensalidade.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'mensalidade.Turma_id')
            ->join('salas', 'salas.id', '=', 'mensalidade.Sala_id')
            ->join('classes', 'classes.id', '=', 'mensalidade.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'mensalidade.Anolectivo_id')
            ->select(
            	'mensalidade.id',
                'mensalidade.Propina_Anual',
                'mensalidade.ConfirmacaoPreco',
                'mensalidade.MatriculaPreco',
                'nomeCurso',
                'nomePeriodo',
                'nomeTurma',
                'nomeSala',
                'classe_name',
                'ano_lectivo',
                'mensalidade.Classe_id',
                'mensalidade.Anolectivo_id',
                'mensalidade.Curso_id',
                'mensalidade.Sala_id',
                'mensalidade.Turma_id',
                'mensalidade.Periodo_id'
            )
            ->where('mensalidade.id', '=', $mensalidade->id)
            ->first();

            //$SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($request->input('classeID'),$request->input('anolectivoID'),$request->input('studentID'));
			//$PagamentoMensal = Pagamento::PagamentoMensal($mensalidades->Anolectivo_id, $mensalidades->Classe_id);
			//$mensalidades->PagamentoMensal = round($PagamentoMensal,2);

        DB::commit();
        return response()->json(['mensalidades' => $mensalidades], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 422);
    }
}











}
