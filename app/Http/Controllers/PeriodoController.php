<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class PeriodoController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
            
       $periodo = DB::table('periodos')->select('id','nomePeriodo')->get();
        return response()->json($periodo);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                   // Valida os dados do pedido
    $validator = Validator::make($request->all(), [
        'nomePeriodo' => 'required|unique:periodos',
// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


            // Create a new instance of your model
            $periodo = new Periodo();
            // Assign the values ​​from the request to the model's properties
            $periodo->nomePeriodo = $request->input('nomePeriodo');
            $periodo->save();
    

   // If no error occurred, return a success response
    return response()->json(['messagem' => 'Dados armazenados com sucesso','periodo'=>$periodo], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    
       $periodo = Periodo::find($id);


        if (!$periodo) {
            return response()->json(['messagem' => 'Periodo não encontrado']);
        }

   
       

    return response()->json($periodo);
    }



    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id)
    {
        // Find the Periodo record by ID
        $periodo = Periodo::findOrFail($id);

        // Check if the nomePeriodo value has changed
        if ($request->input('nomePeriodo') !== $periodo->nomePeriodo) {
            // If the nomePeriodo value has changed, validate for uniqueness
            $request->validate([
                'nomePeriodo' => 'required|unique:periodos,nomePeriodo,' . $id,
            ]);
        }

        // Update the nomePeriodo field
        $periodo->nomePeriodo = $request->input('nomePeriodo');
        $periodo->save();

        // You can add additional logic here if needed

        return response()->json(['message' => 'Periodo updated successfully','periodo'=>$periodo]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
             $deletePeriodo = Periodo::find($id);

        if (!$deletePeriodo) {
            return response()->json(['error' => 'Recurso não encontrado']);
        }

        $deletePeriodo->delete();

        return response()->json(['messagem' => 'Recurso excluído com sucesso']);
    }
}
