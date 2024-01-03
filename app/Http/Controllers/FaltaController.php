<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faltas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;
use DB;

class FaltaController extends Controller
{
 
// ...

public function Falta(Request $request)
{
    DB::beginTransaction();
    try {
        // Validate the incoming request data
        $request->validate([
            'idattendance_types' => 'required',
            'studentID' => 'required',
            'classeID' => 'required',
            'anolectivoID' => 'required',
            'attendance_date' => 'required|date',
            'disciplinaID' => 'required',
        ]);

        // Create or update the attendance record
        $Faltas = Faltas::updateOrCreate([
            'studentID' => $request->studentID,
            'classeID' => $request->classeID,
            'anolectivoID' => $request->anolectivoID,
            'disciplinaID' => $request->disciplinaID,
            'attendance_date' => $request->attendance_date,
        ], [
            'idattendance_types' => $request->idattendance_types,
        ]);





        DB::commit();
            $estudantefalta = FaltaController::faltasdados($Faltas->id);

        return response()->json(['estudantefalta'=>$estudantefalta], 201);

    } catch (ValidationException $e) {
        DB::rollBack();
        // Handle validation errors
        return response()->json([
            'message' => 'Erro ao criar registro de presença.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        // Handle any other exceptions that occur during the process
        return response()->json([
            'message' => 'Erro ao criar registro de presença.',
            'error' => $e->getMessage(),
        ], 500);
    }
}






public static function faltasdados($id){


$faltas =DB::table('attendance')
->join('users', 'users.id', '=', 'attendance.studentID')
->join('classes', 'classes.id', '=', 'attendance.classeID')
->join('ano_lectivos', 'ano_lectivos.id', '=', 'attendance.anolectivoID')
->join('attendance_types', 'attendance_types.id', '=', 'attendance.idattendance_types')
->join('disciplinas', 'disciplinas.id', '=', 'attendance.disciplinaID')
->select(
    'attendance.id as id',
    'attendance_Nome',
    'nomeDisciplina',
    'classe_name',
    'ano_lectivo',
    'attendance_date',
    DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS estudante"))
->where(['attendance.id'=>$id])
->first();

return $faltas;
}



}
