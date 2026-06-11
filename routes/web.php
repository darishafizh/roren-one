<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;

// Auth Routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Portal Routes
Route::get('/greetings', [PortalController::class, 'greetings'])->name('greetings');
Route::get('/users', [PortalController::class, 'users'])->name('users');

// KNMP Routes
Route::prefix('dashboard/knmp')->name('program.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Knmp\DashboardController::class, 'index'])->defaults('program', 'knmp')->name('dashboard');
    Route::get('/export-pdf', [\App\Http\Controllers\Knmp\ExportController::class, 'pdf'])->defaults('program', 'knmp')->name('dashboard.export-pdf');
});
Route::prefix('master/knmp')->name('program.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Knmp\MasterController::class, 'index'])->defaults('program', 'knmp')->name('master');
});
Route::prefix('operasional/knmp')->name('program.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Knmp\OperasionalController::class, 'index'])->defaults('program', 'knmp')->name('operasional');
    Route::post('/upload-foto', [\App\Http\Controllers\Knmp\OperasionalController::class, 'uploadFoto'])->defaults('program', 'knmp')->name('operasional.upload-foto');
});
Route::prefix('evaluasi/knmp')->name('program.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Knmp\EvaluasiController::class, 'index'])->defaults('program', 'knmp')->name('evaluasi');
});

// BINS Routes
Route::prefix('dashboard/bins')->name('program.bins.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Bins\DashboardController::class, 'index'])->defaults('program', 'bins')->name('dashboard');
});
Route::prefix('master/bins')->name('program.bins.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Bins\MasterController::class, 'index'])->defaults('program', 'bins')->name('master');
});
Route::prefix('operasional/bins')->name('program.bins.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Bins\OperasionalController::class, 'index'])->defaults('program', 'bins')->name('operasional');
});
Route::prefix('evaluasi/bins')->name('program.bins.')->group(function () {
    Route::get('/{menu?}', [\App\Http\Controllers\Bins\EvaluasiController::class, 'index'])->defaults('program', 'bins')->name('evaluasi');
});

// Default Program Routes
Route::get('/dashboard/{program}', [\App\Http\Controllers\DefaultProgram\DashboardController::class, 'index'])->name('program.dashboard.default');
Route::get('/master/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\MasterController::class, 'index'])->name('program.master.default');
Route::get('/operasional/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\OperasionalController::class, 'index'])->name('program.operasional.default');
Route::get('/evaluasi/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\EvaluasiController::class, 'index'])->name('program.evaluasi.default');


