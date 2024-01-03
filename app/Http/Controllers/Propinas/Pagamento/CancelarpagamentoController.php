<?php

namespace App\Http\Controllers\Propinas\Pagamento;


use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use App\Pagamentos\CancelarFunctionExtras;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\MyCustomFuctions\Pagamento;
class CancelarpagamentoController extends Controller
{
    



 




/**
 * Cancelar Pagamento
 *
 * @OA\Post(
 *     path="/api/CancelarPagamento",
 *     tags={"Pagamentos"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id"},
 *             @OA\Property(property="id", type="integer", description="ID do pagamento a ser cancelado", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cancelamento bem-sucedido",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Cancelamento bem-sucedido")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erro ao cancelar pagamento",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="ID inválido")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Erro ao cancelar pagamento",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="O pagamento já foi cancelado")
 *         )
 *     )
 * )
 */


 
public function CancelarPagamento(Request $request)
{
    if (empty($request->id) || !is_numeric($request->id)) {
        return response()->json(['error' => 'ID inválido '], 400);
    }

    DB::beginTransaction();
    try {
        $payment = Payment::findOrFail($request->id);
        $FocionarioID = Auth::id();
        $paymentOrder = Pagamento::PaymentOrdercancel();
        // Check if 'Cancelar' is equal to 0, ignore otherwise
        if ($payment->Cancelar == 0) {
            $payment->Cancelar = 1;
            $payment->save();


            $pagamento = Payment::create([
            'Descount' => $payment->Descount,
            'ValorPago' => $payment->ValorPago,
            'classID' => $payment->classID,
            'studentID' => $payment->studentID,
            'anolectivoID' => $payment->anolectivoID,
            'FocionarioID' => $FocionarioID,
            'paymentOrder' => $paymentOrder,
            'Cancelar' => 0,
            'TipodePagementoID'=>$payment->TipodePagementoID,
            'InvoiceType' => 2,
            'bancoid' =>$payment->bancoid,
            'info' =>$payment->info ,
            'fc'=>$payment->id
            ]);
  


             CancelarFunctionExtras::CancelarTransacoesServico($payment->id);
            CancelarFunctionExtras::CancelarConfirmacaoOrMatricula($payment->id);
            CancelarFunctionExtras::CancelarTransactions($payment->id);

            DB::commit();
            return response()->json(['message' => 'Cancelamento bem-sucedido'], 200);
        } else {
            throw new \Exception('O pagamento já foi cancelado');
        }
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}








 










}
