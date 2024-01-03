<?php

namespace App\MyCustomFuctions;

use App\Models\DisciplinaParaClasse;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Meses;
use App\Models\Notas;
use App\Models\Role;
use App\Models\Transactions;
use App\Models\User;
use App\Models\classes;
use Carbon\Carbon;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Str;
use App\MyCustomFuctions\Pagamento;
use App\Models\Municipio;
use App\Models\Paises;
use App\Models\Provincia;


class paisesProvinciaMunicipio
{


public static function SalvarPais ($paisNome){
$paises = DB::table('paises')->where(['Nome'=>$paisNome])->select('Nome','id')->first();

if ($paises) {
return $paises;
}

$paises = Paises::create([
       'Nome' =>$paisNome,
   ]);
return $paises;

}





public static function SalvarProvincia ($provinciaNome,$paisId){

$provincias = DB::table('provincias')->where(['Nome'=>$provinciaNome, 'paisId'=>$paisId])->select('Nome','paisId','id')->first();

if ($provincias) {
return $provincias;
}

$provincias = Provincia::create([
'Nome' =>$provinciaNome,
'paisId' => $paisId,
]);
return $provincias;

}







public static function Salvarmunicipio ($municipioNome,$provinciaId){

$municipios = DB::table('municipios')->where(['Nome'=>$municipioNome,'provinciaId'=>$provinciaId])->select('Nome','provinciaId','id')->first();

if ($municipios) {
return $municipios;
}

$municipios = Municipio::create([
'Nome' =>$municipioNome,
'provinciaId' => $provinciaId,
]);
return $municipios;

}









}
