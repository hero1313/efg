<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectPlanController;
use App\Http\Controllers\GuaranteeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;





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



Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
// project & plan
    // Route::get('/', [ProjectController::class, 'index'])->name('index.project');
    Route::get('/', [DashboardController::class, 'index'])->name('index.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index.dashboard');

    Route::get('projects', [ProjectController::class, 'index'])->name('index.project');
    Route::get('project/{id}', [ProjectController::class, 'show'])->name('show.project');
    Route::post('add-project', [ProjectController::class, 'store'])->name('store.project');
    Route::put('update-project/{id}', [ProjectController::class, 'update'])->name('update.project');
    Route::delete('delete-project/{id}', [ProjectController::class, 'destroy'])->name('delete.project');

    // guarantee
    Route::post('add-project-plane/{id}', [ProjectPlanController::class, 'store'])->name('store.project_plane');
    Route::put('update-project-plane/{id}', [ProjectPlanController::class, 'update'])->name('update.project_plane');
    Route::put('update-project-guarantee-status/{id}', [ProjectPlanController::class, 'updateGuaranteeStatus'])->name('update.guarantee_status');
    Route::delete('delete-project-plane/{id}', [ProjectPlanController::class, 'destroy'])->name('delete.project_plane');

    Route::get('guarantees', [GuaranteeController::class, 'index'])->name('index.guarantee');
    Route::get('guarantee/{id}', [GuaranteeController::class, 'show'])->name('show.guarantee');
    Route::post('add-guarantee', [GuaranteeController::class, 'store'])->name('store.guarantee');
    Route::put('update-guarantee/{id}', [GuaranteeController::class, 'update'])->name('update.guarantee');
    Route::delete('delete-guarantee/{id}', [GuaranteeController::class, 'destroy'])->name('delete.guarantee');

    Route::get('project-forecast', [ProjectController::class, 'projectForecast'])->name('index.forecast');
    Route::put('update-forecast', [ProjectController::class, 'updateForecast'])->name('update.forecast');



    Route::get('download-pdf/{id}', [PdfController::class, 'downloadPDF'])->name('download.pdf');

});