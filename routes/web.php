<?php

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('admin/kpi', [App\Http\Controllers\kpiController::class, 'showKpi'])->name('kpi');
Route::post('admin/kpi', [App\Http\Controllers\kpiController::class, 'createKpi'])->name('post_kpi');
Route::get('admin/db-kpi', [App\Http\Controllers\kpiController::class, 'dbkpi'])->name('db_kpi');
Route::get('admin/create-kpi', [App\Http\Controllers\kpiController::class, 'showCreateKpi'])->name('show_create_kpi');
Route::get('logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

