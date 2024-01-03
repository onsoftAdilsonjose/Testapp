<?php

namespace App\MyCustomFuctions;
use DB;
use Illuminate\Support\Facades\Validator;


class Anolectivo
{
   



public static function TodosAnolectivo(){
 $anolectivo = DB::table('ano_lectivos')->select('id')->get();
 return $anolectivo;


}
public static function UnicoAnolecto(){
 $anolectivo = DB::table('ano_lectivos')->select('id')->first();


}
public static function AnolectivoComaparameters($anolectivoId){
 $anolectivo = DB::table('ano_lectivos')->select('id')->where(['id'=>$anolectivoId])->first();

}

}


 