<?php

namespace App\Http\Controllers;

use App\Models\classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
 
      
         $classes  = DB::table('classes')->select('id','classe_name','ClassComExam')->get();

        if (!$classes) {
        return response()->json(['error' => 'classes  não encontrado.'], 404);
        }

        return response()->json(['Classes' => $classes]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           // Valida os dados do pedido
    $validator = Validator::make($request->all(), [
        'classe_name' => 'required|unique:classes|max:25|',
        //'ClassComExam' => 'integer',
// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


            // Create a new instance of your model
            // $classes = new classes();
            // $classes->classe_name = $request->input('classe_name');
            //  $classes->ClassComExam = $request->input('ClassComExam');
            // $classes->save();
    

   // If no error occurred, return a success response
    //return response()->json(['messagem' => 'Dados armazenados com sucesso','classes'=>$classes], 201);
    return response()->json(['messagem' => 'Dados armazenados com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $classes = classes::find($id);

        if (!$classes) {
            return response()->json(['error' => 'classes  não encontrado.'], 404);
        }

        return response()->json(['Classes' => $classes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
                // Valida os dados do pedido
        $validatedData = $request->validate([
        'classe_name' => 'required|max:30|unique:classes,classe_name,'.$id,
        'ClassComExam' => 'integer',
        ]);

        // Encontra o registro pelo ID
        $classes = classes::find($id);

       // Se o registro não for encontrado, retorna uma resposta de erro
        if (!$classes) {
            return response()->json(['error' => 'Registro não encontrado.'], 404);
        }

        // Update the record with the validated data
        // $classes->classe_name = $validatedData['classe_name'];
        // $classes->ClassComExam = $validatedData['ClassComExam'];
        // //Salva as alterações no banco de dados
        // $classes->save();

      // Retorna uma resposta de sucesso
      // return response()->json(['Classe'=> $classes,'success'=>'Classe Actualizado com sucesso'],200);
       return response()->json(['success'=>'Classe Actualizado com sucesso'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
         $deleteclasses = classes::find($id);

        if (!$deleteclasses) {
            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }

        //$deleteclasses->delete();

        return response()->json(['messagem' => 'Recurso excluído com sucesso']);
    }

    
}
