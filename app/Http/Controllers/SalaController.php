<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
 
 
         $sala = DB::table('salas')->select('id','nomeSala')->get();
        if (!$sala) {
        return response()->json(['error' => 'Salas  não encontrado.'], 404);
        }
        return response()->json(['Sala' => $sala]);






    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
        'nomeSala' => 'required|unique:salas',
// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


            // Create a new instance of your model
            $sala = new Sala();
            // Assign the values ​​from the request to the model's properties
            $sala->nomeSala = $request->input('nomeSala');
            $sala->save();
    

   // If no error occurred, return a success response
    return response()->json(['messagem' => 'Dados armazenados com sucesso','sala'=>$sala], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

            $sala = Sala::find($id);

            if (!$sala) {
            return response()->json(['error' => 'Sala  não encontrado.'], 404);
            }
            return response()->json(['Sala' => $sala]);

    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
                                // Valida os dados do pedido
        $validatedData = $request->validate([
        'nomeSala' => 'required|max:30|unique:salas,nomeSala,'.$id,

        ]);

        // Encontra o registro pelo ID
        $sala = Sala::find($id);

       // Se o registro não for encontrado, retorna uma resposta de erro
        if (!$sala) {
            return response()->json(['error' => 'Sala não encontrado.'], 404);
        }

        // Update the record with the validated data
        //$sala->nomeSala = $validatedData['nomeSala'];

        //Salva as alterações no banco de dados
        //$sala->save();

      // Retorna uma resposta de sucesso
       return response()->json(['Sala'=> $sala,'success'=>'Sala Actualizado com sucesso'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
           $deletesala = Sala::find($id);

        if (!$deletesala) {
            return response()->json(['error' => 'Sala não encontrado'],422);
        }

        //$deletesala->delete();

        return response()->json(['messagem' => 'Sala excluído com sucesso'],200);
    }
}
