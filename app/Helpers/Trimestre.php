<?php

namespace App\Helpers;
use DB;
use Illuminate\Support\Facades\Auth;



class Trimestre
{


public static function TrimestreFilter(){
    $Trimestre = [
        [

                "name"=> " 1 Trimestre",
                "id"=> 1,

        ],
                [

                "name"=> " 2 Trimestre",
                "id"=> 2,


        ],

                [

                "name"=> "3 Trimestre",
                "id"=> 3,


        ]
    ];

return  $Trimestre ;




}



public static function TrimestreEscolher($trimestre){




if ($trimestre == 1) {
   $Trimestre ="1_Trimestre";
}elseif($trimestre == 2){
 $Trimestre ="2_Trimestre";
}elseif($trimestre == 3){
 $Trimestre ="3_Trimestre";
}else{
 $Trimestre = "1_Trimestre";
}

   return  $Trimestre ;
}











}


