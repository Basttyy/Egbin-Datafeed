<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages;
use App\Http\Controllers\pagesController;

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


Route::get('/index',[pagesController::class,'index'])->name('index');
 Route::get('login',[pagesController::class,'login'])->name('login');
 Route::get('register',[pagesController::class,'register'])->name('register');
 Route::get('forgot-password',[pagesController::class,'forgotPassword'])->name('forgot_password');
