<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GPTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/chat/recibir-mensaje', [ChatController::class, 'recibirMensajeDeWhatsapp']);
Route::get('/gpt/procesar-prompt', [GPTController::class, 'chat']);

