<?php

namespace App\Http\Controllers;

use App\Models\TipoDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class TipoDisciplinaController extends Controller
{
    

 


    public function index()
    {

        
       $tipodedesciplina = DB::table('tipodesciplina')->select('TipoNome','id')->get();
        

        if (!$tipodedesciplina) {
        return response()->json(['error' => 'Tipodedesciplina  não encontrado.'], 404);
        }
        return response()->json(['Tipodedesciplina' => $tipodedesciplina]);
    }


 
 
public function store(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'TipoNome' => 'required|unique:tipodesciplina',
        // Add more validation rules for other fields as needed
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }

    // Create a new instance of your model
    $tipodedesciplina = new TipoDisciplina();
    // Assign the values from the request to the model's properties
    $tipodedesciplina->TipoNome = $request->input('TipoNome');
    $tipodedesciplina->save();

    // If no error occurred, return a success response
    return response()->json(['messagem' => 'Dados armazenados com sucesso', 'tipodedesciplina' => $tipodedesciplina], 201);
}



 
public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'TipoNome' => 'required|max:55|unique:tipodesciplina,TipoNome,' . $id,
        // Add more validation rules for other fields as needed
    ]);

    // Find the record by ID
    $tipodedesciplina = TipoDisciplina::find($id);

    // If the record is not found, return an error response
    if (!$tipodedesciplina) {
        return response()->json(['error' => 'Tipo de desciplina não encontrado.'], 404);
    }

    // Update the record with the validated data
    $tipodedesciplina->TipoNome = $validatedData['TipoNome'];

    // Save the changes to the database
    $tipodedesciplina->save();

    // Return a success response
    return response()->json(['Tipodedesciplina' => $tipodedesciplina, 'success' => 'Tipo de desciplina Atualizado com sucesso'], 200);
}






 
public function delete($id)
{
    $deletetipodedesciplina = TipoDisciplina::find($id);

    if (!$deletetipodedesciplina) {
        return response()->json(['error' => 'Recurso não encontrado'], 404);
    }

    $deletetipodedesciplina->delete();

    return response()->json(['messagem' => 'Recurso excluído com sucesso'], 200);
}






}
