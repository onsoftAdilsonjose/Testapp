<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class EditarUsersControllers extends Controller
{
    //








    public function geTUsersinfo($id){




$usuario = DB::table('users')
->Leftjoin('pessoa','.pessoa.id','=','users.pessoa_id') // commented out as it's not being used
->select('users.id as id','users.email','users.primeiro_nome','users.ultimo_nome','users.nomePai','users.nomeMae',
'status','users.pessoa_id','users.dataofbirth','users.reg_Numero','users.telefoneAlternativo','users.numeroDotelefone','pessoa.tipoDeDocumento',
'pessoa.pais','pessoa.municipio_id','pessoa.bairro','pessoa.provincia_id','pessoa.genero_id','pessoa.numeroDoDocumento')
->where('users.id', '=', $id)
->first();

return response()->json([
    'unicoRelatorio'  =>$usuario
], 200);
 








        
     }














     public function EditarUsersinfo(Request $request , $id){




        return [$request->all(),$id];
     }







     public function softdelteUsersinfo(Request $request , $id){




        return [$request->all(),$id];
     }





     public function bloqueiar(Request $request , $id){




        return [$request->all(),$id];
     }






}
