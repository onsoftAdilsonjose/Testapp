<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformacoesdePagamento;
use Illuminate\Support\Facades\Validator;

 
class InformacoesdePagamentoController extends Controller
{
 
    public function InformacoesdePaga()
    {
        $InformacoesdePagamento = InformacoesdePagamento::first();
        return response()->json(['InformacoesdePagamento' => $InformacoesdePagamento], 200);
    }

 
    public function storeInformacoesdePagamento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nome' => 'required|max:100',
            'percetagem' => 'required|numeric|min:0|max:100',
            'diaCombraca' => 'required|integer|min:1|max:28',
            'Desconto' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['error' => $firstError], 422);
        }

        $record = InformacoesdePagamento::first();

        if ($record) {
            // Update the existing record
            $data = $record->update([
                'Nome' => $request->input('Nome'),
                'percetagem' => $request->input('percetagem'),
                'diaCombraca' => $request->input('diaCombraca'),
                'Desconto' => $request->input('Desconto'),
            ]);

            return response()->json(['message' => 'Record created or updated successfully', 'data' => $data, 'status' => 200]);
        } else {
            // Create a new record since it doesn't exist
            $data = InformacoesdePagamento::create([
                'Nome' => $request->input('Nome'),
                'percetagem' => $request->input('percetagem'),
                'diaCombraca' => $request->input('diaCombraca'),
                'Desconto' => $request->input('Desconto'),
            ]);

            return response()->json(['message' => 'Record created or updated successfully', 'data' => $data, 'status' => 200]);
        }
    }
}
