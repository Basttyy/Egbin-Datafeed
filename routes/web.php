<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\kpiController;
use App\Http\Controllers\MetricController;
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

Route::get('/', [HomeController::class, 'index']);

Auth::routes();

Route::get('/auth/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('auth.change_password.show');
Route::post('/auth/change-password', [ChangePasswordController::class, 'changePassword'])->name('auth.change_password');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('kpi', [kpiController::class, 'showKpi'])->name('kpi');
Route::post('metric', [MetricController::class, 'createMetric'])->name('post_metric');
Route::get('db-metrics', [MetricController::class, 'dbMetrics'])->name('db_metrics');
Route::get('approved-metrics', [MetricController::class, 'showApprovedMetrics'])->name('approved_metrics');
Route::get('disapproved-metrics', [MetricController::class, 'showDisapprovedMetrics'])->name('disapproved_metrics');
Route::get('sync-metrics', [MetricController::class, 'showPushedMetrics'])->name('synced_metrics');
Route::get('push-metrics', [MetricController::class, 'syncData'])->name('sync_data');
Route::put('/metrics/{id}', [MetricController::class, 'updateMetric'])->name('update_metric');
Route::get('admin/create-kpi', [kpiController::class, 'showCreateKpi'])->name('show_create_kpi');
Route::get('logout', [HomeController::class, 'logout'])->name('logout');
