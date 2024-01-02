<?php

use App\Http\Controllers\Medicine_Order;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\Sales_Controller;
use App\Http\Controllers\Search;
use App\Http\Controllers\UserController;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('order',[Medicine_Order::class,'order']);
Route::post('/medicines', [MedicineController::class, 'store'])->middleware(['CheckAdmin']);
Route::post('/orders', [Medicine_Order::class, 'store']);
Route::get('/orders', [Medicine_Order::class, 'show'])->middleware(['CheckAdmin']);
Route::post('/orders/details', [Medicine_Order::class, 'index']);
Route::post('/orders/status', [Medicine_Order::class, 'changeStatus'])->middleware(['CheckAdmin']);
Route::post('/notify', [Medicine_Order::class, 'getNotification']);
Route::get('/medicines-or-storages', [MedicineController::class, 'getMedicinesOrStorages']);
Route::post('/addToFavorite', [Medicine_Order::class, 'putToFavorite']);
Route::post('/sales', [Sales_Controller::class,'generateReport'])->middleware(['CheckAdmin']);
Route::post('/Usersales', [Sales_Controller::class,'generateReportUser']);
