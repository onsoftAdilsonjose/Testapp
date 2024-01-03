<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index()
    {
           /// e de lembrar o ano lectivo e a Classe terao de ser inserido antes de criar um Curso
            // $anolectivo= DB::table('ano_lectivos')->select('id','ano_lectivo','fim','inicio')->first();  //este daddo sera usado quando quisermos cadastrar um curso e precisara de uma ano lectivo
            // $classe= DB::table('classes')->select('id','classe_name')->get(); /// o curso tambem vai precisar de Classe 
            $curso = DB::table('curso')->select('id','nomeCurso')->get();
            if (!$curso) {
            return response()->json(['error' => 'Cursos  não encontrado or  por favor tentar Criar Uma Classe e um Ano Lectivo'], 404);
            }
            return response()->json(['Cursos' => $curso,'Anolectivo']);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Valida os dados do pedido
    $validator = Validator::make($request->all(), [
        'nomeCurso' => 'required',
        // 'anolectivo_id' => 'required|integer',
        // 'classe_id' => 'required|integer',
        // 'Pagamento_anual' => 'required|numeric',
        // 'ConfirmacaoPreco' => 'required|numeric',
        // 'MatriculaPreco' => 'required|numeric',



// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        return response()->json(['errors' => $errors], 422);
    }


                // Create a new instance of your model
             //   $curso = new Curso();
                // Assign the values ​​from the request to the model's properties
             //   $curso->nomeCurso = $request->input('nomeCurso');
                // $curso->anolectivo_id = $request->input('anolectivo_id');
                // $curso->classe_id = $request->input('classe_id');
                // $curso->Pagamento_anual = $request->input('Pagamento_anual');
                // $curso->ConfirmacaoPreco = $request->input('ConfirmacaoPreco');
                // $curso->MatriculaPreco = $request->input('MatriculaPreco');



                //$curso->save();
    

   // If no error occurred, return a success response
    //return response()->json(['messagem' => 'Dados armazenados com sucesso','curso'=>$curso], 201);
        return response()->json(['messagem' => 'Dados armazenados com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

             $curso = Curso::find($id);

        if (!$curso) {
            return response()->json(['error' => 'Curso  não encontrado.'], 404);
        }

        return response()->json(['Curso' => $curso]);

    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
                // Valida os dados do pedido
        $validatedData = $request->validate([
        'nomeCurso' => 'required|max:30|unique:curso,nomeCurso,'.$id, 
        // 'anolectivo_id' => 'required|integer',
        // 'classe_id' => 'required|integer',
        // 'Pagamento_anual' => 'required|numeric',
        // 'ConfirmacaoPreco' => 'required|numeric',
        // 'MatriculaPreco' => 'required|numeric',
        ]);

        // Encontra o registro pelo ID
            $curso = Curso::find($id);

       // Se o registro não for encontrado, retorna uma resposta de erro
        if (!$curso) {
            return response()->json(['error' => 'Curso não encontrado.'], 404);
        }

        // Update the record with the validated data
            //$curso->nomeCurso = $validatedData['nomeCurso'];
            // $curso->anolectivo_id = $validatedData['anolectivo_id'];
            // $curso->classe_id = $validatedData['classe_id'];
            // $curso->Pagamento_anual = $validatedData['Pagamento_anual'];
            // $curso->ConfirmacaoPreco = $validatedData['ConfirmacaoPreco'];
            // $curso->MatriculaPreco = $validatedData['MatriculaPreco'];

        //Salva as alterações no banco de dados
       // $curso->save();

      // Retorna uma resposta de sucesso
       //return response()->json(['Curso'=> $curso,'success'=>'Curso Actualizado com sucesso'],200);
       return response()->json(['success'=>'Curso Actualizado com sucesso'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        //      $deleteCurso = Curso::find($id);

        // if (!$deleteCurso) {
        //     return response()->json(['error' => 'Curso não encontrado'],404);
        // }

        // $deleteCurso->delete();

        return response()->json(['messagem' => 'Curso excluído com sucesso'],200);
    }
    
}
