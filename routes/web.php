<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Sales_Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('add_medicine',[Admin::class,'add_medicine']);
Route::post('/sales', [Sales_Controller::class,'generateReport']);
