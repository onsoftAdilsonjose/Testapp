<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ProcessoDisciplinar;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Docs;

class ProcessodeDisciplinaresController extends Controller
{
    //

    public function index(){

$ProcessoDisc =DB::table('processodisciplinar')
->join('users', 'users.id', '=', 'processodisciplinar.student_id')
->join('classes', 'classes.id', '=', 'processodisciplinar.Classe_id')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'processodisciplinar.Anolectivo_id')
->select('processodisciplinar.id as id','testemunha','motivo','data','ano_lectivo','classe_name',
DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS estudante"))
->get();





return response()->json(['ProcessoDisc' => $ProcessoDisc], 201);




}








public function storeprocesso(Request $request){

     $userId = Auth::id();

           //Valida os dados do pedido
			$validator = Validator::make($request->all(), [
			'motivo' => 'required|max:200',
			'student_id' => 'required',
			'testemunha' => 'nullable|max:200',
			'data' => 'required',
			'Classe_id' => 'required',
			'Anolectivo_id' => 'required',
			]);

   // Verifica se a validação falha
			if ($validator->fails()) {
			$errors = $validator->errors();
			// Return the validation errors as a JSON response
			return response()->json(['errors' => $errors], 422);
			}


   //          // Create a new instance of your model
			$ProcessoDisciplinar = new ProcessoDisciplinar();
			$ProcessoDisciplinar->motivo = $request->input('motivo');
		    $ProcessoDisciplinar->student_id	= $request->input('student_id');
		    $ProcessoDisciplinar->testemunha = $request->input('testemunha');
			$ProcessoDisciplinar->data	= $request->input('data');
			$ProcessoDisciplinar->Classe_id	= $request->input('Classe_id');
			$ProcessoDisciplinar->Anolectivo_id	= $request->input('Anolectivo_id');
			$ProcessoDisciplinar->registradopor	= $userId;
            $ProcessoDisciplinar->save();
    
			$ProcessoDisc = Docs::processodiscipinardoaluno($ProcessoDisciplinar->id);

			return response()->json(['ProcessoDisc' => $ProcessoDisc], 201);


	
}












}
