<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//http://192.168.201.10/web-api/public/api/productos ---->XAMMP
Route::get('/obtenerPosts',[\App\Http\Controllers\ProductoController::class,'obtenerPosts']);
Route::post('/eliminarPost',[\App\Http\Controllers\ProductoController::class,'eliminarPost']);
Route::post('/guadarPost',[\App\Http\Controllers\ProductoController::class,'guadarPost']);
Route::post('/actualizarPost',[\App\Http\Controllers\ProductoController::class,'actualizarPost']);
Route::post('/subirImagen',[\App\Http\Controllers\ProductoController::class,'subirImagen']);
