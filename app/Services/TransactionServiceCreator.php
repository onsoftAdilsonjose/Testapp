<?php


// app/Services/TransactionServiceCreator.php

namespace App\Services;

use App\Models\TransatiosServico;

class TransactionServiceCreator
{
    public static function createTransactionService($requestData, $payment, $PaymentOrder)
    {
        $transactionServices = [];

        if (isset($requestData['Services']) && !empty($requestData['Services'])) {
            foreach ($requestData['Services'] as $index => $servicesData) {
                // Access the 'id', 'qtd', and 'Preco' properties from the $servicesData array
                $servicesID = $servicesData['id'];
                $qtd = $servicesData['qtd'];
                $preco = $servicesData['Preco'];

                // Check if the 'desconto' key exists, and if it does, use its value; otherwise, default to 0
                $descontoServico = isset($servicesData['desconto']) ? $servicesData['desconto'] : 0;

                $transactionServices[] = TransatiosServico::create([
                    'servicoID' => $servicesID,
                    'payment_id' => $payment->id,
                    'Preco' => $preco,
                    'Quantidade' => $qtd,
                    'classID' => $requestData['classeID'],
                    'studentID' => $requestData['studentID'],
                    'anolectivoID' => $requestData['anolectivoID'],
                    'paymentOrder' => $PaymentOrder,
                    'Cancelar' => 0,
                    'Descount' => $descontoServico,
                ]);
            }
        }

        return $transactionServices;
    }
}
