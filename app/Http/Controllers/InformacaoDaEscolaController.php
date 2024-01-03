<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformacaoDaEscola;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Throwable;

class InformacaoDaEscolaController extends Controller
{
        public function View()
    {




$escolas = DB::table('informacaoescola')
->select(
    'id',
'nomeDaempresa',
'numeroDaescola',
'endereco',
'nif',
'pais',
'cidade',
'municipio',
'bairro',
'telefoneAlternativo',
'numeroDotelefone',
'email',
'Site',
'logo'


)->first();
  
    return response()->json(['escolas'=>$escolas], 200);



    }





public function creatOrupdate(Request $request)
{
    // Validate the incoming request data
    $request->validate([
         'nomeEscola' => 'required|string',
        //'numeroDaescola' => 'required|string',
        'endereco' => 'required|string',
        'nif' => 'required|string',
        // 'pais' => 'required|string',
        // 'cidade' => 'required|string',
        // 'municipio' => 'required|string',
        //'bairro' => 'required|string',
        'telefoneAlt' => 'nullable|string',
        'telefone' => 'required|string',
        'email' => 'required|email',
        'Site' => 'nullable|url',
        //'logo' => 'nullable|string',
    ]);

    // Check if the record already exists based on email
    $existingEscola = InformacaoDaEscola::where('email', $request->input('email'))->first();

    if ($existingEscola) {
        // Update the existing record with the new data
        $existingEscola->update([
             'nomeDaempresa' => $request->input('nomeEscola'),
            'numeroDaescola' => $request->input('numeroEscola'),
            'endereco' => $request->input('endereco'),
            'nif' => $request->input('nif'),
            //'pais' => $request->input('pais'),
            //'cidade' => $request->input('cidade'),
            //'municipio' => $request->input('municipio'),
            'bairro' => $request->input('bairro'),
            'telefoneAlternativo' => $request->input('telefoneAlt'),
            'numeroDotelefone' => $request->input('telefone'),
            'email' => $request->input('email'),
            'Site' => $request->input('Site'),
           // 'logo' => $request->input('logo'),
        ]);

        return response()->json([
            'InformacaoDaEscola' => $existingEscola,
            'message' => 'Escola updated successfully!',
        ], 200);
    } else {
        // Create a new record if it doesn't exist
        $newEscola = InformacaoDaEscola::create([
             'nomeDaempresa' => $request->input('nomeEscola'),
            'numeroDaescola' => $request->input('numeroEscola'),
            'endereco' => $request->input('endereco'),
            'nif' => $request->input('nif'),
            //'pais' => $request->input('pais'),
            //'cidade' => $request->input('cidade'),
            //'municipio' => $request->input('municipio'),
            'bairro' => $request->input('bairro'),
            'telefoneAlternativo' => $request->input('telefoneAlt'),
            'numeroDotelefone' => $request->input('telefone'),
            'email' => $request->input('email'),
            'Site' => $request->input('Site'),
           // 'logo' => $request->input('logo'),
        ]);

        return response()->json([
            'InformacaoDaEscola' => $newEscola,
            'message' => 'Escola created successfully!',
        ], 200);
    }
}




}
