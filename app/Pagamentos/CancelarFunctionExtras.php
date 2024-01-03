<?php

namespace App\Pagamentos;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\TransatiosServico;
use App\Models\Transactions;
use App\Models\ConfirmacaOrMatricula;


class CancelarFunctionExtras
{
   

public static function CancelarTransacoesServico($cancelar)
{
    $transacoesServico = TransatiosServico::where('payment_id', $cancelar)->get();

    if ($transacoesServico->isNotEmpty()) {
        foreach ($transacoesServico as $transacaoServico) {
            $transacaoServico->update(['Cancelar' => 1]);
        }
    }
}




public static function CancelarConfirmacaoOrMatricula($cancelar)
{
    $confirmacaoOrMatricula = ConfirmacaOrMatricula::where('payment_id', $cancelar)->first();

    if ($confirmacaoOrMatricula) {
        $confirmacaoOrMatricula->update(['cancelar' => 1]);
    }
}




public static function CancelarTransactions($cancelar)
{
    $transactions = Transactions::where('payment_id', $cancelar)->get();

    if ($transactions->isNotEmpty()) {
        foreach ($transactions as $transaction) {
            $transaction->update(['Cancelar' => 1]);
        }
    }
}











}












 
 