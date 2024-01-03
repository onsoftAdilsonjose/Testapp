<?php


// app/Services/PaymentCreator.php

namespace App\Services;

use App\Models\Payment;
use App\Pagamentos\PagarFunctionExtras;

class PaymentCreator
{
    public static function createPayment($request, $FocionarioID, $PaymentOrder)
    {
        $infoschool = PagarFunctionExtras::infoschool();
        $infoestudante = PagarFunctionExtras::infoestudante($request->input('studentID'), $request->input('anolectivoID'));
        $infoantedence = PagarFunctionExtras::infoantedence($FocionarioID);

        $payment = Payment::create([
            'Descount' => 0,
            'ValorPago' => $request->input('total'),
            'classID' => $request->input('classeID'),
            'studentID' => $request->input('studentID'),
            'anolectivoID' => $request->input('anolectivoID'),
            'FocionarioID' => $FocionarioID,
            'paymentOrder' => $PaymentOrder,
            'Cancelar' => 0,
            'TipodePagementoID' => $request->input('metodoId'),
            'InvoiceType' => 1,
            'bancoid' => $request->input('contaBancaria'),
            'info' => json_encode(['infoschool' => $infoschool, 'infoestudante' => $infoestudante, 'infoantedence' => $infoantedence]),
        ]);

        return $payment;
    }
}
