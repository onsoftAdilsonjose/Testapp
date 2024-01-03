<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    

        
          $disciplina  = DB::table('disciplinas')->select('id','nomeDisciplina','ProvaOral')->get();

            if (!$disciplina) {
            return response()->json(['error' => 'Disciplinas  não encontrado.'], 404);
            }
            return response()->json(['Disciplinas' => $disciplina]);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                  // Valida os dados do pedido
    $validator = Validator::make($request->all(), [
        'nomeDisciplina' => 'required|unique:disciplinas',

// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


            // Create a new instance of your model
            $disciplina = new Disciplina();
            // Assign the values ​​from the request to the model's properties
            $disciplina->nomeDisciplina = $request->input('nomeDisciplina');
            $disciplina->ProvaOral = $request->input('ProvaOral');
            $disciplina->save();
    

   // If no error occurred, return a success response
    return response()->json(['messagem' => 'Disciplina armazenado com sucesso','disciplina'=>$disciplina], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
           
            $disciplina = Disciplina::find($id);

            if (!$disciplina) {
            return response()->json(['error' => 'Disciplina  não encontrado.'], 404);
            }

            return response()->json(['Disciplina' => $disciplina]);
    }

 
    /** 
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
                        // Valida os dados do pedido
        $validatedData = $request->validate([
        'nomeDisciplina' => 'required|max:30|unique:disciplinas,nomeDisciplina,'.$id,

        ]);

        // Encontra o registro pelo ID
        $disciplina = Disciplina::find($id);

       // Se o registro não for encontrado, retorna uma resposta de erro
        if (!$disciplina) {
            return response()->json(['error' => 'Disciplina não encontrado.'], 404);
        }

        // Update the record with the validated data
        $disciplina->nomeDisciplina = $validatedData['nomeDisciplina'];
        $disciplina->ProvaOral = $validatedData['ProvaOral'];
        //Salva as alterações no banco de dados
        $disciplina->save();

      // Retorna uma resposta de sucesso
       return response()->json(['Disciplina'=> $disciplina,'success'=>'Disciplina Actualizado com sucesso'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
            $deletedisciplina = Disciplina::find($id);

            if (!$deletedisciplina) {
            return response()->json(['error' => 'Disciplina não encontrado'],404);
            }

            $deletedisciplina->delete();

            return response()->json(['messagem' => 'Disciplina excluído com sucesso'],200);
    }
}
