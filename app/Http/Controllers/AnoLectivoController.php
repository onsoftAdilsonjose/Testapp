<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Meses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB; // Import the DB facade

class AnoLectivoController extends Controller
{
    /**
     * Exibir uma listagem do recurso.
     */
    public function index()
    {
        //


$anoLectivo  = DB::table('ano_lectivos')->get();
        if (!$anoLectivo) {
            return response()->json(['error' => 'Ano Lectivos  não encontrados.'], 404);
        }

        return json_encode(['AnoLectivo' => $anoLectivo]);
        
    }



    public function store(Request $request)
    {
        $startDate = Carbon::parse($request->inicio);
        $endDate = Carbon::parse($request->fim);
        $currentDate = $startDate;
        $lastInsertedId = null;
        $yearinicio = $startDate->year;
        $yearfim = $endDate->year;
        $YerInicioYearFim = $yearinicio . '/' . $yearfim;

        $existingRecord = AnoLectivo::where('ano_lectivo', $YerInicioYearFim)->first();

        if ($existingRecord) {
            return response()->json(['errors' => 'Este Ano Lectivo já esta Registrado'], 422);
        }

        DB::beginTransaction(); // Begin the database transaction
        try {
            $request->validate([
                'inicio' => 'required|date',
                'fim' => 'required|date|after:inicio',
            ]);

            $anoLectivo = new AnoLectivo();
            $anoLectivo->ano_lectivo = $YerInicioYearFim;
            $anoLectivo->inicio = $startDate;
            $anoLectivo->fim = $endDate;
            $anoLectivo->save();

$orderNumber = 1;

while ($currentDate->lte($endDate)) {
    $lastInserted = Meses::create([
        'mesNome' => $currentDate->format('F'),
        'mesID' => $currentDate->format('n'),
        'mesPercetagemDesconto' => 0,
        'mesAnularPagamento' => false,
        'mesAnolectivoID' => $anoLectivo->id,
        'ClassComExam' => false,
        'Data' => $currentDate->format('Y-m-d'),
        'orderNumber' => $orderNumber
    ]);

    $lastInsertedId = $lastInserted->id;
    $currentDate = $currentDate->addMonth();
    $orderNumber++; // Increment the order number for the next iteration
}

            if ($lastInsertedId) {
                $lastRecord = Meses::find($lastInsertedId);
                if ($lastRecord) {
                    $lastRecord->update([
                        'ClassComExam' => true,
                    ]);
                }
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();
            return response()->json($e->errors(), 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            DB::rollback();
            return response()->json(['message' => 'An error occurred.'], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Data stored successfully.'], 200);
    }





    /**
     * Display the specified resource.
     */
    public function show($id)
    {


        $anoLectivo = AnoLectivo::find($id);

        if (!$anoLectivo) {
            return response()->json(['error' => 'Ano lectivo  não encontrado.'], 404);
        }

        return response()->json(['anoLectivo' => $anoLectivo]);
    }



    public function update(Request $request, $id)
    {
        $anoLectivo = AnoLectivo::find($id);

        if (!$anoLectivo) {
            return response()->json(['errors' => 'Ano Lectivo not found.'], 404);
        }

        $startDate = Carbon::parse($request->inicio);
        $endDate = Carbon::parse($request->fim);
        $currentDate = $startDate;
        $yearinicio = $startDate->year;
        $yearfim = $endDate->year;
        $YerInicioYearFim = $yearinicio . '/' . $yearfim;

        // Check if another record with the same "ano_lectivo" exists
        $existingRecord = AnoLectivo::where('ano_lectivo', $YerInicioYearFim)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRecord) {
            return response()->json(['errors' => 'This Ano Lectivo is already registered.'], 422);
        }

        DB::beginTransaction(); // Begin the database transaction
        try {
            $request->validate([
                'inicio' => 'required|date',
                'fim' => 'required|date|after:inicio',
            ]);

            $anoLectivo->ano_lectivo = $YerInicioYearFim;
            $anoLectivo->inicio = $startDate;
            $anoLectivo->fim = $endDate;
            $anoLectivo->save();

            // Delete existing Meses records for the AnoLectivo
            Meses::where('mesAnolectivoID', $anoLectivo->id)->delete();

            while ($currentDate->lte($endDate)) {
                $lastInserted = Meses::create([
                    'mesNome' => $currentDate->format('F'),
                    'mesID' => $currentDate->format('n'),
                    'mesPercetagemDesconto' => 0,
                    'mesAnularPagamento' => false,
                    'mesAnolectivoID' => $anoLectivo->id,
                    'ClassComExam' => false,
                    'Data' => $currentDate->format('Y-m-d'),
                ]);

                $lastInsertedId = $lastInserted->id;
                $currentDate = $currentDate->addMonth();
            }

            if ($lastInsertedId) {
                $lastRecord = Meses::find($lastInsertedId);
                if ($lastRecord) {
                    $lastRecord->update([
                        'ClassComExam' => true,
                    ]);
                }
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollback();
            return response()->json($e->errors(), 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            DB::rollback();
            return response()->json(['message' => 'An error occurred.'], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Data updated successfully.'], 200);
    }



    public function delete($id)
    {
        $deleteAnolectivo = AnoLectivo::find($id);

        if (!$deleteAnolectivo) {
            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }

        $deleteAnolectivo->delete();

        return response()->json(['messagem' => 'Recurso excluído com sucesso'], 204);
    }
}
