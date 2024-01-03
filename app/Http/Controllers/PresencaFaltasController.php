<?php


namespace App\Http\Controllers;

use App\Models\PresencaFaltas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\DisciplinaParaClasse;
use Illuminate\Support\Facades\Validator;
class PresencaFaltasController extends Controller
{


 
    // Retrieve all records
    public function showstudents($classeId,$AnoLectivoID,$DisciplinaID,$PeriodoID,$TurmaID)
    {
        // $presencaFaltas = PresencaFaltas::all();
        // 
            $DisciplinaParaClasse = DisciplinaParaClasse::join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->join('estudante_x_ano_x_classe', 'estudante_x_ano_x_classe.Classe_id', '=', 'disciplinaparaclasse.Classe_id')
            // ->join('alunos', 'alunos.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->where('Disciplina_id', $classeId)
            ->where('estudante_x_ano_x_classe.Anolectivo_id',$AnoLectivoID) 
            // ->where('Classe_id', $classeID)
            // ->where('Anolectivo_id', $anolectivoID)
            // ->where('Classe_id', $DisciplinaID)
            ->get();




           return response()->json(['message' => $DisciplinaParaClasse], 200);

    }








    // Create a new record
    public function store(Request $request){

      // Valida os dados do pedido
    $validator = Validator::make($request->all(), [
                'idattendance_types' => 'required|integer|',
                'studentID' => 'required|integer',
                'classeID' => 'required|integer',
                'anolectivoID' => 'required|integer',
                'attendance_date' => 'required|date|',
                'disciplinaID' => 'required|integer',            

// Adicione mais regras de validação para outros campos conforme necessário
    ]);

   // Verifica se a validação falha
    if ($validator->fails()) {
        $errors = $validator->errors();
        // Return the validation errors as a JSON response
        
    }

                // Create a new instance of your model
                $PresencaFaltas = new PresencaFaltas();
                // Assign the values ​​from the request to the model's properties
                $PresencaFaltas->idattendance_types = $request->input('idattendance_types');
                $PresencaFaltas->studentID = $request->input('studentID');
                $PresencaFaltas->anolectivoID = $request->input('anolectivoID');
                $PresencaFaltas->classeID = $request->input('classeID');
                $PresencaFaltas->attendance_date = $request->input('attendance_date');
                $PresencaFaltas->disciplinaID = $request->input('disciplinaID');

                // Assign other properties as needed
                // Save the model to the database
                $PresencaFaltas->save();


   // If no error occurred, return a success response
    return response()->json(['messagem' => 'Dados armazenados com sucesso','PresencaFaltas'=>$PresencaFaltas], 200);





    }











    // Retrieve a single record
    public function show($id)
    {
        $presencaFaltas = PresencaFaltas::find($id);
        if ($presencaFaltas) {
            return response()->json($presencaFaltas);
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }

    // Update a record
    public function update(Request $request, $id)
    {
        $presencaFaltas = PresencaFaltas::find($id);
        if ($presencaFaltas) {
            $presencaFaltas->update($request->all());
            return response()->json($presencaFaltas);
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }

    // Delete a record
    public function destroy($id)
    {
        $presencaFaltas = PresencaFaltas::find($id);
        if ($presencaFaltas) {
            $presencaFaltas->delete();
            return response()->json(['message' => 'Record deleted']);
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }
}
