<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class FilterController extends Controller
{
    //


    public function classesApi()
    {
 

     $anoLectivo   = DB::table('ano_lectivos')->first();

    




        $classesApi= DB::table('classes')
        ->join('mensalidade', 'mensalidade.Classe_id', '=', 'classes.id')
         ->where('mensalidade.Anolectivo_id','=',$anoLectivo->id)
        ->select(
        	//'mensalidade.id as classeapiId',
        	'classe_name','classes.id as id')->get();
        return response()->json([ 'Classes'=> $classesApi]);

    }






            public function cursoApi($apiId)
            {

            $anoLectivo = DB::table('ano_lectivos')->first();
            $cursoApi = DB::table('curso')
            ->join('mensalidade', 'mensalidade.Curso_id', '=', 'curso.id')
            ->select('mensalidade.Curso_id as id', 'nomeCurso')
            ->where('mensalidade.Anolectivo_id', '=', $anoLectivo->id)
            ->where('mensalidade.Classe_id', '=', $apiId)
            ->distinct()
            ->get('mensalidade.Curso_id');
            return response()->json(['Cursos' => $cursoApi]);
            }







    public function periodoApi($periodoapId,$cursoid)
    {


          $anoLectivo   = DB::table('ano_lectivos')->first();


			$periodoApi= DB::table('periodos')
			->join('mensalidade', 'mensalidade.Periodo_id', '=', 'periodos.id')

			->select('mensalidade.Periodo_id as id','nomePeriodo')
			 ->where('mensalidade.Anolectivo_id','=',$anoLectivo->id)
			->where('mensalidade.Classe_id','=',$periodoapId)

             ->distinct()
			->get('mensalidade.Periodo_id');
        return response()->json([ 'periodo'=> $periodoApi]);

    }









 



    public function turmaApi($turmaApiId,$cursoid,$periodo)
    {

            $anoLectivo   = DB::table('ano_lectivos')->first();
			$turmapi= DB::table('turmas')
			->join('mensalidade', 'mensalidade.Turma_id', '=', 'turmas.id')
            ->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
			->select('mensalidade.Turma_id as id','nomeTurma')
			 ->where('mensalidade.Anolectivo_id','=',$anoLectivo->id)
			->where('mensalidade.Classe_id','=',$turmaApiId)
            ->where('mensalidade.Periodo_id','=',$periodo)
             ->where('mensalidade.Curso_id','=',$cursoid)
            ->distinct()
			->get('mensalidade.Turma_id');
        return response()->json([ 'Turmas'=> $turmapi]);

    }





    public function salaApi($salaApiId,$cursoid,$periodo,$turma)
    {
      $anoLectivo   = DB::table('ano_lectivos')->first();


            $salaApi= DB::table('salas')
            ->join('mensalidade', 'mensalidade.Sala_id', '=', 'salas.id')
            ->join('curso', 'curso.id', '=', 'mensalidade.Curso_id')
            ->select('mensalidade.Sala_id as id','nomeSala')
             ->where('mensalidade.Anolectivo_id','=',$anoLectivo->id)
            ->where('mensalidade.Classe_id','=',$salaApiId)
            ->where('mensalidade.Periodo_id','=',$periodo)
            ->where('mensalidade.Curso_id','=',$cursoid)
            ->where('mensalidade.Turma_id','=',$turma)
            ->distinct()
            ->get('mensalidade.Sala_id');
        return response()->json([ 'Sala'=> $salaApi]);

    }









    public function cursodisciplinaclasseApi($cursoid,$classid)
    {
 
            $cursodisciplinaclasseApi = DB::table('cursodisciplinaclasse')
            ->join('disciplinas', 'disciplinas.id', '=', 'cursodisciplinaclasse.disciplinaId')
            ->join('mensalidade', 'mensalidade.Curso_id', '=', 'cursodisciplinaclasse.cursoId')
            ->select('nomeDisciplina','disciplinas.id as id')
            ->where('cursodisciplinaclasse.cursoId','=',$cursoid)
            ->where('cursodisciplinaclasse.classId','=',$classid)
            ->where('mensalidade.Curso_id','=',$cursoid)
            ->where('mensalidade.Classe_id','=',$classid)

            ->distinct()
			 ->get('disciplinas.id');
        return response()->json([ 'Disciplinas'=> $cursodisciplinaclasseApi]);

    }









    public function AnolectivoApi()
    {
 

        $anolectivo= DB::table('ano_lectivos')->select('id','ano_lectivo')->first();

        return response()->json([ 'anolectivoActual'=> $anolectivo]);

    }





 




    public function MesesApi($anolectivoID)
    {
 

        $meses= DB::table('meses')
        ->where(['mesAnolectivoID'=>$anolectivoID])
        ->select('mesNome','mesID')->get();

        return response()->json([ 'meses'=> $meses]);

    }











}
