<?php

use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

    Route::post('/inicio',[CuestionarioController::class,'enpezarcondni'])->name('enpezar');

    Route ::post('/obtenerdatos',[CuestionarioController::class,'obtenerdatos'])->name('obtener');

    Route::get('/cerrarsession',[CuestionarioController::class,'logout'])->name('logout');

    Route::post('enviarrespuestas',[TestController::class,'enviar_resuestas']);
    Route::post('enviarrespuesta_pregu',[TestController::class,'enviar_resultado']);
    Route::get('cuestionario/{token}',[CuestionarioController::class,'datos_link']);


  /*
     Route::get('cuestionario/{token}/{key}',function ($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
           $char = substr($string, $i, 1);
           $keychar = substr($key, ($i % strlen($key))-1, 1);
           $char = chr(ord($char)-ord($keychar));
           $result.=$char;
        }
        return $result;
     });
    */