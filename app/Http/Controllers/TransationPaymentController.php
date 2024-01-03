<?php

namespace App\Http\Controllers;

use App\Jobs\RegistrationJob;
use App\Jobs\confirmationDepagamento;
use App\Models\AnoLectivo;
use App\Models\EstudanteSaldo;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Meses;
use App\Models\Payment;
use App\Models\PaymentDetalhes;
use App\Models\Servico;
use App\Models\Transactions;
use App\Models\TransatiosServico;
use App\Models\ConfirmacaOrMatricula;
use App\MyCustomFuctions\MatricularEstudante;
use App\MyCustomFuctions\MinhasFuncoes;
use App\MyCustomFuctions\Pagamento;
use App\MyCustomFuctions\Customised;
use App\Rules\ConditionalValueRule;
use App\Rules\ContaBancariaRequired;
use Carbon\Carbon;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;


class TransationPaymentController extends Controller
{

    /*** Display a listing of the resource.*/


    /***.*/    public function findEstudantes()
    {

        $Estudante_x_Ano_x_Classe = Pagamento::Estudante_x_Ano_x_Classes();




        return response()->json(['EstudanteSelecionado' => $Estudante_x_Ano_x_Classe]);

        if (!$Estudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Estudante não encontrado.'], 404);
        }

        //return response()->json(['EstudanteSelecionado' => $Estudante_x_Ano_x_Classe]);
    }









    public function DetalhesPayment($classeId, $anolectivoID, $studentID)
    {





        // $validator = Validator::make($request->all(), [
        // 'metodoId' => 'required|integer|exists:users,id',
        // 'value' => 'required|integer|exists:mensalidade,Periodo_id',
        // 'contaBancaria' => 'required|integer|exists:mensalidade,Turma_id',
        // 'esquecerMulta' => 'required|integer|exists:mensalidade,Sala_id',
        // 'total' => 'required|integer|exists:mensalidade,Classe_id',
        // 'subTotal' => 'required|integer|exists:mensalidade,Curso_id',
        // 'Anolectivo_id' => 'required|integer|exists:mensalidade,Anolectivo_id'
        // ]);

        // if ($validator->fails()) {
        // $errors = $validator->errors();
        // return response()->json($errors, 422);
        // }




// Months
// CountMeses
// MesesComDivida
// Estudante_x_Ano_x_Classe
//Estudante_x_Ano_x_Classe

 
    // $classeId = 11;
    // $anolectivoID = 1;
    // $studentID = 240;
  

        $SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($classeId,$anolectivoID,$studentID);

        $Estudante_x_Ano_x_Classe  = Pagamento::Estudante_x_Ano_x_Classe($studentID, $anolectivoID, $classeId,$SingleStudentDetalhes);


        $Saldo = Pagamento::Saldo($studentID);
        $estudanteSaldo = (int)$Saldo;
        $months = Pagamento::Months($classeId,$SingleStudentDetalhes);
        $countMeses = Pagamento::CountMeses($anolectivoID, $classeId,$SingleStudentDetalhes);


        $MesComDivida = Pagamento::MesesComDivida($studentID, $anolectivoID, $classeId,$SingleStudentDetalhes);
        
        $PagarApartir = Pagamento::PagarApartir($studentID, $anolectivoID, $classeId);
        $MesesPago = Pagamento::MesesPago($studentID, $anolectivoID, $classeId);









        $PagamentoMensal = Pagamento::PagamentoMensal($anolectivoID, $classeId,$SingleStudentDetalhes);


        $CountMesComDivida = count($MesComDivida);
        $Divida =$CountMesComDivida * $PagamentoMensal;

        $resultArray = MinhasFuncoes::checkMonths($months, $anolectivoID, $studentID, $classeId,$SingleStudentDetalhes);
        $MesesComMultas = $resultArray['totalCount'];
        $mesesIDComMulta = $resultArray['MesesComMultas'];
        $ValorDaMulta = MinhasFuncoes::calcularjuros($MesesComMultas * $PagamentoMensal);



        $multaOrMulta = DB::table('multa')->select('percetagem', 'diaCombraca', 'Desconto')->first();



        if (!$Estudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Estudante não encontrado.'], 404);
        }


        return response()->json([
            'PagarApartir' => $PagarApartir,
            'EstudanteDetalhes' => $Estudante_x_Ano_x_Classe,
            'MescomDivida' => $MesComDivida,
            //'MetodePagamento' => $MetodePagamento,
            'PagamentoMensal' => round($PagamentoMensal,2),
            'Divida' => round($Divida,2),
            'EstudanteSaldo' => $estudanteSaldo,
            'countMeses' => $countMeses,
            'MesesComMultas' => $MesesComMultas,
            'ValorDaMulta' => round($ValorDaMulta,2),
            //'mesesNomeComMulta' => $mesesNomeComMulta,
            'mesesNomeComMulta' => $resultArray['MesesComMultas'],



        ]);
    }

    public function EstudantePayment(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'metodoId' => 'required|integer|exists:tipodepagamento,id',
            'contaBancaria' => [new ContaBancariaRequired,],
            'total' => 'required|numeric',
            'value' => ['required', 'numeric', new ConditionalValueRule],
            'esquecerMulta' => 'boolean',
            'Pagarcomsaldo' => 'boolean',
        ]);


        if ($validator->fails()) {
        $errors = $validator->errors();
        $firstError = $errors->first(); // Get the first error message

        return response()->json(['error' => $firstError], 422);
        }



        DB::beginTransaction();
        try {

             $SingleStudentDetalhes = Pagamento::SingleStudentDetalhes($request->input('classeID'),$request->input('anolectivoID'),$request->input('studentID'));
            $CheckMeses = $request->input('Meses');
            $mescomDivida  = Pagamento::MesesComDivida($request->studentID, $request->anolectivoID, $request->classeID,$SingleStudentDetalhes);
            $VerificarMeses  = Pagamento::VerificarMeses($CheckMeses, $request->studentID, $request->anolectivoID, $request->classeID);
            $estadodepagamento = Customised::ConfirmacaoMatriculaPago($request->studentID, $request->anolectivoID,$request->classeID);
            $PaymentOrder = Pagamento::PaymentOrder();
            $meses = $request->input('Meses');
            $mescomDividaOrder = collect($mescomDivida)->pluck('mesNome')->toArray();
            $mesesOrder = collect($meses)->pluck('mesNome')->toArray();
            $filteredMescomDividaOrder = array_intersect($mescomDividaOrder, $mesesOrder);
            $saldoDescontado = 0;
            $saldoguardado = 0;
         
            $MatricularEstudante = MatricularEstudante::MatricululaorConfirmacao($request->input('studentID'),$request->input('anolectivoID'),$request->input('classeID'));

            if ($filteredMescomDividaOrder !== $mesesOrder) {
                return response()->json(['error' => 'Ordem de Meses não está sincronizada com Meses com Divida'], 422);
            }

            if ($VerificarMeses === false) {
                return response()->json(['error' => 'Os Meses Nao Podem ser duplicados'], 422);
            }




            if ($request->value < $request->total && $request->pagamentoPorSaldo == true) {
            $saldoAserPago = $request->total - $request->value;

            $studentSaldo = Pagamento::Saldo($request->studentID);

            if ($studentSaldo < $saldoAserPago) {
            return response()->json(['error' => 'Transação não pôde ser concluída devido à falta de saldo suficiente'], 422);
            }


            $saldoDescontado =  Pagamento::pagamentoPorSaldo($request->studentID,$saldoAserPago);
            $Novovalue = $request->value + $saldoDescontado;
            $Novototal = $request->total;

            } elseif($request->value > $request->total) {

            $saldoAserGuardado = $request->value - $request->total; 
            $saldoguardado =  Pagamento::saldoAserGuardado($request->studentID,$saldoAserGuardado);
            $Novototal =$saldoAserGuardado + $request->total; 
            $Novovalue = $request->value - $saldoAserGuardado;

            }elseif($request->value == $request->total){
            $Novovalue = $request->value;
            $Novototal = $request->total;

            }


 




            $payment = Payment::create([
                'Descount' => 0,
                'ValorPago' => $Novototal,
                'classID' => $request->input('classeID'),
                'studentID' => $request->input('studentID'),
                'anolectivoID' => $request->input('anolectivoID'),
                'FocionarioID' => 1,
                'paymentOrder' => $PaymentOrder,
                'Cancelar' => 0,
                'TipodePagementoID' => $request->input('anolectivoID'),
                'SaldoRemovido' =>$saldoDescontado,
                'SaldoGuardado'=>$saldoguardado
            ]);

               $MatricululaorConfirmacao = [];

           // if ($estadodepagamento == false && $request->has('MatricularEstudante') && !empty($request->input('MatricularEstudante'))) {
             
            // $MatricululaorConfirmacao = ConfirmacaOrMatricula::create([
            //     'student_id' =>$request->input('studentID'),
            //     'Classe_id' => $request->input('classeID'),
            //     'Anolectivo_id' => $request->input('anolectivoID'),
            //     'Preco' => $request->MatricularEstudante['Preco'],
            //     'paymentOrder' => $PaymentOrder,
            //     'payment_id' => $payment->id,
            //     'matriculaorconfirmacaoId' =>$request->MatricularEstudante['Servico'],
            // ]);

           // }


 
                $transactions = [];


            if ($request->has('Meses') && !empty($request->input('Meses'))) {
                foreach ($request->input('Meses') as $index => $mesData) {
                    // Access the 'mesID' and 'valorTotal' properties from the $mesData object
                    $mesID = $mesData['mesID'];
                    $pagamentoMensal = $mesData['pagamentoMensal'];
                    // Check if the 'Multa' key exists in $mesData array

                    $Multa  = isset($mesData['multa']) ? $mesData['multa'] : 0;
                    $Esquecermulta = ($request->esquecerMulta) ? 0:$Multa  ;

                    $DescontoMeses = isset($mesData['desconto']) ? $mesData['desconto'] : 0;

                    // Insert new record for each $mesID
                    $transactions = Transactions::create([
                        'payment_id' => $payment->id,
                        'classID' => $request->input('classeID'),
                        'studentID' => $request->input('studentID'),
                        'anolectivoID' => $request->input('anolectivoID'),
                        'MesesID' => $mesID,
                        'Preco' => round($pagamentoMensal,2),
                        'paymentOrder' => $PaymentOrder,
                        'Multa' => $Esquecermulta,
                        'Descount' => $DescontoMeses,
                        'Cancelar' => 0,
                        // Add other fields that you want to set here
                    ]);



                }
            }




            if ($request->has('Services') && !empty($request->input('Services'))) {
                // Initialize an array to collect the service IDs
                $servicesToUpdate = [];

                foreach ($request->input('Services') as $index => $ServicesData) {
                    // Access the 'id', 'qtd', and 'Preco' properties from the $ServicesData array
                    $ServicesID = $ServicesData['id'];
                    $qtd = $ServicesData['qtd'];
                    //$PrecoQu = $ServicesData['Preco'] * $qtd;
                    $DescontoServico = $ServicesData['desconto'];
                    $DescontoServico = ($DescontoServico !== null) ? $DescontoServico  : 0;
                    $qtdToRemove = $qtd;
                    $qtdToAdd = $qtd;

                    // Collect the service ID for update
                    $servicesToUpdate[] = $ServicesID;

                    // Insert new record for each service
                    $transactionServices = TransatiosServico::create([
                        'servicoID' => $ServicesID,
                        'payment_id' => $payment->id,
                        'Preco' => $ServicesData['Preco'],
                        'Quantidade' => $qtd,
                        'classID' => $request->input('classeID'),
                        'studentID' => $request->input('studentID'),
                        'anolectivoID' => $request->input('anolectivoID'),
                        'paymentOrder' => $PaymentOrder,
                        'Cancelar' => 0,
                        'Descount' => $DescontoServico,
                    ]);
                }

                // Remove duplicates and preserve the array keys
                //$servicesToUpdate = array_values(array_unique($servicesToUpdate));

                // Update services lembrar que quando se tiver que cancelar o pagamento e necessario usar o mesmo processo de devolucaao da quantidade no servico
                // $updateServico = Servico::whereIn('id', $servicesToUpdate)
                //     ->update([
                //         'TotalVendido' => DB::raw("TotalVendido + $qtdToAdd"),
                //         'QuantidadeExiste' => DB::raw("QuantidadeExiste - $qtdToRemove")
                //     ]);
            }



               ///confirmationDepagamento::dispatch($transactions, $request->Meses);
               //RegistrationJob::dispatch($transactions, $request->Meses);
              
 


//logica the pagamento de Mensalidadae



$estadodepagamento = Customised::ConfirmacaoMatriculaPago($request->input('studentID'), $request->input('anolectivoID'), $request->input('classeID'));
$MatriculapagaValue = $estadodepagamento['Matriculapaga'];
if ($MatriculapagaValue === false) {
$preco = $request->input('MatricularEstudante.Preco');
$servico = $request->input('MatricularEstudante.Servico');
$request->input('studentID');
 $request->input('anolectivoID'); 
 $request->input('classeID');
$MatricularEstudante = $request->merge(['MatricularEstudante' => [
'Preco' => $preco,
'Servico' => $servico,
//'estado'=>false
]]);


            $MatricululaorConfirmacao = ConfirmacaOrMatricula::create([
                'student_id' =>$request->input('studentID'),
                'Classe_id' => $request->input('classeID'),
                'Anolectivo_id' => $request->input('anolectivoID'),
                'Preco' => $preco,
                'paymentOrder' => $PaymentOrder,
                'payment_id' => $payment->id,
                'matriculaorconfirmacaoId' => $servico,
                // 'matriculaorconfirmacaoId' =>$request->MatricularEstudante['Servico'],
            ]);


}













            DB::commit();
            return response()->json([
            'message' => $request->all(),
            'SaldoRemovido' => $saldoDescontado,
            'SaldoGuardado' => $saldoguardado,
            'NumeroFactura' => $PaymentOrder,
            'ValorPago' => $Novovalue,
            'MatricularEstudante'=>$MatricularEstudante,
            'MatricululaorConfirmacao'=>$MatricululaorConfirmacao,
            'transactions'=>$transactions,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    /**
     * Store a newly created resource in storage.
     */


    /**
     * Update the specified resource in storage.
     */
    public function cancelarFactura(Request $request)
    {

$transatiosServico = TransatiosServico::where('payment_id', $request->paymentId)->get();
$transactions = Transactions::where('payment_id', $request->paymentId)->latest()->get();
$payment = Payment::where('id', $request->paymentId)->first();





if ($payment) {
    // $payment->update(['Cancelar' => 1, ]);
}


if ($transatiosServico) {
foreach ($transatiosServico as $index => $servico) {
    // Transactions::where('payment_id', $servico->payment_id)->update([
    //     'Cancelar' => 1,
    //     // Add other fields that you want to update here
    // ]);
}
}


if ($transactions) {
foreach ($transactions as $index => $Propina) {
    // Transactions::where('payment_id', $Propina->payment_id)->update([
    //     'Cancelar' => 1,
    //     // Add other fields that you want to update here
    // ]);

}






}





 




 return response()->json(['success' => $payment,$transactions,$transatiosServico], 200);







    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
