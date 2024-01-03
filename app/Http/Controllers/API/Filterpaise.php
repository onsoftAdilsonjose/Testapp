<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Municipio;
use App\Models\Paises;
use App\Models\Provincia;
use App\MyCustomFuctions\paisesProvinciaMunicipio;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Filterpaise extends Controller
{
    //


    public function getPaises()
    {
        $paises = DB::table('paises')
            ->select('id', 'Nome')
            ->get();
        return response()->json(['paises' => $paises]);
    }





    public function getProvincia($paisId)
    {

        $provincia = DB::table('provincias')
            ->select('id', 'Nome')
            ->where(['paisId' => $paisId])
            ->get();
        return response()->json(['provincia' => $provincia]);
    }



    public function getMunicipio($provinciaId)
    {

        $municipio = DB::table('municipios')
            ->select('Nome', 'provinciaId', 'id')
            ->where(['provinciaId' => $provinciaId])
            ->get();
        return response()->json(['municipio' => $municipio]);
    }






    public function Pais(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'paisNome' => 'required|string',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first(); // Get the first error message
            return response()->json(['error' => $firstError], 422);
        }


        // Create a new record in the database
        $paises = Paises::create([
            'Nome' => $request->input('paisNome'),
        ]);

        return response()->json(['paises' => $paises]);
    }







    public function Provincia(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'proviciaNome' => 'required|string',
            // 'paisId' => 'required|integer|exists:paises,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first(); // Get the first error message
            return response()->json(['error' => $firstError], 422);
        }




        // Create a new record in the database
        $provincia = Provincia::create([
            'Nome' => $request->input('proviciaNome'),
            'paisId' => $request->input('paisId'),
        ]);

        return response()->json(['provincia' => $provincia]);
    }









    public function Municipio(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'municipiosNome' => 'required|string',
            'provinciaId' => 'required|integer|exists:provincias,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first(); // Get the first error message
            return response()->json(['error' => $firstError], 422);
        }



        // Create a new record in the database
        $municipio = Municipio::create([
            'Nome' => $request->input('municipiosNome'),
            'provinciaId' => $request->input('provinciaId'),
        ]);

        return response()->json(['municipio' => $municipio]);
    }





    public function paisesProvinciaMunicipio(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'municipiosNome' => 'required|string',
            'proviciaNome' => 'required|string',
            'paisNome' => 'required|string',
            //'provinciaId' => 'required|integer|exists:provincias,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first(); // Get the first error message
            return response()->json(['error' => $firstError], 422);
        }




        DB::beginTransaction();
        try {

            $paises = paisesProvinciaMunicipio::SalvarPais($request->paisNome);
            $provincia = paisesProvinciaMunicipio::SalvarProvincia($request->proviciaNome, $paises->id);
            $municipio = paisesProvinciaMunicipio::Salvarmunicipio($request->municipiosNome, $provincia->id);





            return response()->json([
                'paises' => $paises,
                'provincia' => $provincia,
                'municipio' => $municipio
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
