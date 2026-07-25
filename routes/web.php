<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RankingController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('criterias', CriteriaController::class);
Route::resource('alternatives', AlternativeController::class);
Route::post('/alternatives/import', [AlternativeController::class, 'import'])->name('alternatives.import');
Route::get('/alternatives/template/download', [AlternativeController::class, 'downloadTemplate'])->name('alternatives.template');
Route::delete('/alternatives-all', [AlternativeController::class, 'destroyAll'])->name('alternatives.destroyAll');
Route::get('/calculation', [CalculationController::class, 'index'])->name('calculation.index');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
Route::get('/report/pdf', [ReportController::class, 'pdf'])->name('report.pdf');
