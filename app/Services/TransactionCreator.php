<?php



// app/Services/TransactionCreator.php

namespace App\Services;

use App\Models\Transactions;

class TransactionCreator
{
    public static function createTransactions($request, $payment, $PaymentOrder)
    {
        $transactions = [];

        if ($request->has('Meses') && !empty($request->input('Meses'))) {
            foreach ($request->input('Meses') as $index => $mesData) {
                // Access the 'mesID' and 'valorTotal' properties from the $mesData object
                $mesID = $mesData['mesID'];
                $pagamentoMensal = $mesData['pagamentoMensal'];

                // Check if the 'Multa' key exists in $mesData array
                $Multa = isset($mesData['multa']) ? $mesData['multa'] : 0;
                $Esquecermulta = ($request->esquecerMulta) ? 0 : $Multa;
                $DescontoMeses = isset($mesData['desconto']) ? $mesData['desconto'] : 0;

                // Insert new record for each $mesID
                $transactions[] = Transactions::create([
                    'payment_id' => $payment->id,
                    'classID' => $request->input('classeID'),
                    'studentID' => $request->input('studentID'),
                    'anolectivoID' => $request->input('anolectivoID'),
                    'MesesID' => $mesID,
                    'Preco' => round($pagamentoMensal, 2),
                    'paymentOrder' => $PaymentOrder,
                    'Multa' => $Esquecermulta,
                    'Descount' => $DescontoMeses,
                    'Cancelar' => 0,
                    // Add other fields that you want to set here
                ]);
            }
        }

        return $transactions;
    }
}
