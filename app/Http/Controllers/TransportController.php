<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transporte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;
use DB;
class TransportController extends Controller
{
    //
     // Get a list of all transport routes
    public function index()
    {
        $transportes = Transporte::all();

        
        return response()->json($transportes);
    }





    // Get a specific transport route by ID
    public function show($id)
    {
        $transporte = Transporte::findOrFail($id);
        return response()->json($transporte);
    }

    // Create a new transport route
    public function store(Request $request)
    {
        $request->validate([
            'nome_rota' => 'required|string',
            'preco' => 'required|numeric',
            'municipio' => 'required|string',
            'bairro' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $transporte = Transporte::create($request->all());
        return response()->json($transporte, 201);
    }

    // Update an existing transport route
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome_rota' => 'string',
            'preco' => 'numeric',
            'municipio' => 'string',
            'bairro' => 'string',
            'status' => 'boolean',
        ]);

        $transporte = Transporte::findOrFail($id);
        $transporte->update($request->all());
        return response()->json($transporte, 200);
    }

    // Delete a transport route by ID
    public function destroy($id)
    {
        $transporte = Transporte::findOrFail($id);
        $transporte->delete();
        return response()->json(null, 204);
    }
}
