<?php

use App\Http\Controllers\Search;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register',[UserController::class,'register'])->middleware(['Register_middleware']);
Route::post('login',[UserController::class,'login'])->middleware(['login_middleware']);
Route::post('logout',[UserController::class,'logout']);



Route::post('search',[Search::class,'search']);




Route::post('/medicines_2', [MedicineController::class, 'store_2']);
Route::post('/medicines', [MedicineController::class, 'store']);
