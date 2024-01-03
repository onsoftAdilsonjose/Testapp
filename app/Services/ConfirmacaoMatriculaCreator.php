<?php

// app/Services/ConfirmacaoMatriculaCreator.php

namespace App\Services;

use App\Models\ConfirmacaOrMatricula;

class ConfirmacaoMatriculaCreator
{
    public static function createConfirmacaoMatricula($request, $payment, $PaymentOrder)
    {
        $existepagamento = ConfirmacaOrMatricula::where([
            'student_id' => $request->studentID,
            'Anolectivo_id' => $request->anolectivoID,
            'Classe_id' => $request->classeID,
            'cancelar'=>0,
        ])->exists();

        if ($existepagamento == false) {
            $inscricao = $request->input('inscricao');
            $Preco = $inscricao['Preco'];
            $Servico = $inscricao['Servico'];

            ConfirmacaOrMatricula::create([
                'student_id' => $request->input('studentID'),
                'Classe_id' => $request->input('classeID'),
                'Anolectivo_id' => $request->input('anolectivoID'),
                'Preco' => $Preco,
                'paymentOrder' => $PaymentOrder,
                'payment_id' => $payment->id,
                'matriculaorconfirmacaoId' => $Servico,
            ]);

            // You can return null or any other indicator if needed
        }

       
    }




 


}
