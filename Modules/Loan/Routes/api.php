<?php

use Modules\Loan\Http\Controllers\LoanController;

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
Route::middleware('auth:sanctum')->name('loan.')->group(function () {
    Route::post('/loan', [LoanController::class, 'store'])->name('store');
    Route::get('/loan/{id}', [LoanController::class, 'view'])->name('view');
    Route::put('/loan/{id}', [LoanController::class, 'update'])->name('update');
    Route::delete('/loan/{id}', [LoanController::class, 'delete'])->name('delete');
    Route::get('/loans', [LoanController::class, 'list'])->name('list');
});