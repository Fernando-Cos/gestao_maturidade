<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('homeavaliador', [App\Http\Controllers\HomeController::class, 'index'])->name('homeavaliador');
Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');


//DIAGNÓSTICOS
Route::get('/diagnostico/showIndices/{id_unidade}', [App\Http\Controllers\TbDiagnosticoController::class, 'showIndices'])->name('showIndices');
Route::get('/diagnostico/showIndicesSubAreas/{id_unidade}/{id_area}', [App\Http\Controllers\TbDiagnosticoController::class, 'showIndicesSubAreas'])->name('showIndicesSubAreas');
Route::get('/diagnostico/showUltimoDiagnostico/{id_unidade}', [App\Http\Controllers\TbDiagnosticoController::class, 'showUltimoDiagnostico'])->name('showUltimoDiagnostico');
Route::post('/diagnostico/exibirPerguntas', [App\Http\Controllers\TbDiagnosticoController::class, 'exibirPerguntas'])->name('exibirPerguntas');
Route::post('/diagnostico/atualizarIndices', [App\Http\Controllers\TbDiagnosticoController::class,'atualizarIndices'])->name('atualizarIndices');
Route::post('/diagnostico/salvarRespostas', [App\Http\Controllers\TbDiagnosticoController::class,'salvarRespostas']);
Route::post('/diagnostico/consultarPontosFortesFracos', [App\Http\Controllers\TbDiagnosticoController::class,'consultarPontosFortesFracos'])->name('consultarPontosFortesFracos');
Route::post('/diagnostico/resultModeloArea',[App\Http\Controllers\TbDiagnosticoController::class,'resultModeloArea'])->name('resultModeloArea');
