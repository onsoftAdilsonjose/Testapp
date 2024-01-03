<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Illuminate\Support\Arr;
use App\Helpers\Trimestre;

class DadosUteisController extends Controller
{
    //


    public function ContaBancaria()
    {


        $ContaBancaria = DB::table('banco')->select('id', 'NDECONTA', 'IBAN', 'BANCO')->get();

        if (!$ContaBancaria) {
            return response()->json(['error' => 'ContaBancaria  não encontrado.'], 404);
        }
        return response()->json(['ContaBancaria' => $ContaBancaria]);
    }






    public function MetodePagamento()
    {


        $MetodePagamento = DB::table('tipodepagamento')->select('id', 'Tipodepagamento')->get();

        if (!$MetodePagamento) {
            return response()->json(['error' => 'MetodePagamento  não encontrado.'], 404);
        }
        return response()->json(['MetodePagamento' => $MetodePagamento]);
    }


/**
 *filtro de Trimestre Para Estudante Logado.
 *
 * @OA\Get (
 *     path="/api/trimestrefilter",
 *     tags={"Usuário autenticado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Filtro de Trimestre",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer",example=1),
 *                 @OA\Property(property="name", type="string",example="1 Trimestre"),
 *             )
 *         )
 *     )
 * )
 */

    public function trimestrefilter()
    {


        $Trimestre = Trimestre::TrimestreFilter();

        return response()->json(['Trimestre' => $Trimestre]);
    }
}
