<?php

namespace App\Http\Controllers;


use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Arr;
class ServicoController extends Controller
{



/**
 * Get a list of all services
 *
 * @OA\Get(
 *     path="/api/Admin/Servico",
 *     tags={"Servico"},
 *     @OA\Response(
 *         response=200,
 *         description="List of services",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="Preco", type="string", example="10.00"),
 *                 @OA\Property(property="ServicoNome", type="string", example="Service Name"),
 *                 @OA\Property(property="QuantidadeExiste", type="integer", example=100),
 *                 @OA\Property(property="TotalVendido", type="integer", example=500)
 *             )
 *         )
 *     )
 * )
 */
public function index()
{
    $servicos = DB::table('servicos')
        ->select('Preco', 'ServicoNome', 'QuantidadeExiste', 'TotalVendido','id')
        ->get();

    return response()->json(['services' => $servicos], 200);
}

 










}
