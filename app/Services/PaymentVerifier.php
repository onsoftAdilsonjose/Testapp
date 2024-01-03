<?php


// app/Services/PaymentVerifier.php

namespace App\Services;

use App\MyCustomFuctions\Pagamento;
use App\MyCustomFuctions\Customised;

class PaymentVerifier
{
    public static function verifyPayment($request)
    {
        $SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($request->input('classeID'), $request->input('anolectivoID'), $request->input('studentID'));
        $CheckMeses = $request->input('Meses');
        $mescomDivida = Pagamento::MesesComDivida($request->studentID, $request->anolectivoID, $request->classeID, $SingleStudentDetalhes);
        $VerificarMeses = Pagamento::VerificarMeses($CheckMeses, $request->studentID, $request->anolectivoID, $request->classeID);
        $estadodepagamento = Customised::ConfirmacaoMatriculaPago($request->studentID, $request->anolectivoID, $request->classeID);
        
        $meses = $request->input('Meses');
        $mescomDividaOrder = collect($mescomDivida)->pluck('mesNome')->toArray();
        $mesesOrder = collect($meses)->pluck('mesNome')->toArray();
        $filteredMescomDividaOrder = array_intersect($mescomDividaOrder, $mesesOrder);

        if ($filteredMescomDividaOrder !== $mesesOrder) {
            return response()->json(['error' => 'Ordem de Meses não está sincronizada com Meses com Dívida'], 422);
        }

        if ($VerificarMeses === false) {
            return response()->json(['error' => 'Os Meses Não Podem ser duplicados'], 422);
        }

        return null; // Indicates successful verification
    }
}
