<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // Selecting the "id" and "nomeTurma" columns from the "turmas" table
$turma = DB::table('turmas')->select('id', 'nomeTurma')->get();

    
    if (!$turma) {
    return response()->json(['error' => 'Turma  não encontrado.'], 404);
    }
    return response()->json(['Turmas' => $turma]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'nomeTurma' => 'required|unique:turmas',
// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


            // Create a new instance of your model
           // $turma = new Turma();
            // Assign the values ​​from the request to the model's properties
           // $turma->nomeTurma = $request->input('nomeTurma');
           // $turma->save();
    

   // If no error occurred, return a success response
    return response()->json(['messagem' => 'Dados armazenados com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $turma = Turma::find($id);

        if (!$turma) {
        return response()->json(['error' => 'Turma  não encontrado.'], 404);
        }
        return response()->json(['Turmas' => $turma]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
                                        // Valida os dados do pedido
        $validatedData = $request->validate([
        'nomeTurma' => 'required|max:30|unique:turmas,nomeTurma,'.$id,
        ]);
        // Encontra o registro pelo ID
        $turma = Turma::find($id);
       // Se o registro não for encontrado, retorna uma resposta de erro
        if (!$turma) {
            return response()->json(['error' => 'turma não encontrado.'], 404);
        }
        // Update the record with the validated data
        //$turma->nomeTurma = $validatedData['nomeTurma'];

        //Salva as alterações no banco de dados
       // $turma->save();
      // Retorna uma resposta de sucesso
       return response()->json(['Turma'=> $turma,'success'=>'Turma Actualizado com sucesso'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $deleteTurma = Turma::find($id);

        if (!$deleteTurma) {
            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }

        //$deleteTurma->delete();

        return response()->json(['messagem' => 'Recurso excluído com sucesso'],200);
    }
}
