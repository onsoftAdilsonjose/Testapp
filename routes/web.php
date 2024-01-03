<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Barcode\ScanController;
// use App\Http\Controllers\PDFController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



//  Route::get('Estudante', [ScanController::class, 'VerestudanteComMultas']);
// Route::get('Listaestudante', [ScanController::class, 'Listaestudante'])->middleware('web');
// //Route::get('removeData/{id}', [DataController::class, 'removeData']);

 Route::middleware(['web'])->group(function () {

 Route::get('Estudante', [ScanController::class, 'VerestudanteComMultas']);
Route::get('Listaestudante', [ScanController::class, 'Listaestudante']);


    // ... your web routes
});