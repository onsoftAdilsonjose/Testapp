<?php

namespace App\Professor;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Disciplina;
use App\Models\Notas;
use App\Models\AnoLectivo;
use App\Models\DisciplinaParaClasse;
class Professorfunctions
{
   



 public static function faltasdados ($faltasid){



      $professorId = Auth::id();
   

         $estudantefaltas = DB::table('attendance')
        ->join('users', 'users.id', '=', 'attendance.studentID')
        ->join('classes', 'classes.id', '=', 'attendance.classeID')
        ->join('ano_lectivos', 'ano_lectivos.id', '=', 'attendance.anolectivoID')
        ->join('attendance_types', 'attendance_types.id', '=', 'attendance.idattendance_types')
        ->join('disciplinas', 'disciplinas.id', '=', 'attendance.disciplinaID')
        ->join('disciplinaparaclasse', 'disciplinaparaclasse.Disciplina_id', '=', 'disciplinas.id')
        ->select(
        DB::raw("CONCAT(users.primeiro_nome, ' ', users.ultimo_nome) as full_name"),
            'users.reg_Numero',
            'disciplinas.id as disciplina_id',
            'attendance_Nome',
            'attendance_date',
            'nomeDisciplina',
            'classe_name',
            'ano_lectivo',
            'attendance.id as attendanceid',
            DB::raw("SUM(CASE WHEN attendance_types.id = 1 THEN 1 ELSE 0 END) AS presente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 2 THEN 1 ELSE 0 END) AS ausente_count"),
            DB::raw("SUM(CASE WHEN attendance_types.id = 3 THEN 1 ELSE 0 END) AS atrasado_count")
        )
			->where([
			'attendance.attendanceid' => $faltasid, 
			'disciplinaparaclasse.Professor_id' =>$professorId,
			])
        ->groupBy('disciplinas.id', 'attendance_Nome','attendance_date', 'nomeDisciplina', 'classe_name', 'ano_lectivo','full_name','reg_Numero')
        ->get();

 
       return  $estudantefaltas;
  
}



}


 