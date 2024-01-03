<?php

namespace App\Http\Controllers\Propinas\Pagamento;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionServiceCreator;
use App\Services\TransactionCreator;
use App\Services\ConfirmacaoMatriculaCreator;
use App\Services\PaymentVerifier;
use App\MyCustomFuctions\Pagamento;
use App\Services\PaymentCreator;
use App\Pagamentos\PagarFunctionExtras;
use App\Http\Requests\EstudantePaymentRequest;
class PagmentodeMensalidadeController extends Controller
{
     









     
                public function EstudantePayment(EstudantePaymentRequest $request)
            {
                
                 $validatedData = $request->validated();

                 DB::beginTransaction();
                 try {

                    $FocionarioID = Auth::id();
                    $verificationResult = PaymentVerifier::verifyPayment($request);

                    $PaymentOrder = Pagamento::PaymentOrder();
                    if ($verificationResult) {
                    // Handle the error response
                        return $verificationResult;
                    }
                    $payment = PaymentCreator::createPayment($request, $FocionarioID, $PaymentOrder);
                    $transactions = TransactionCreator::createTransactions($request, $payment, $PaymentOrder);
                    $transactionServices = TransactionServiceCreator::createTransactionService($request->all(), $payment, $PaymentOrder);
                    $confirmationOrMatricula = ConfirmacaoMatriculaCreator::createConfirmacaoMatricula($request, $payment, $PaymentOrder);

                    DB::commit();
                    $Factura = PagarFunctionExtras::Factura($payment->id);
                    return response()->json([
                        'Pagamento' => $Factura
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => $e->getMessage()], 422);
                }
            }






}
