<?php

use Modules\Repayment\Http\Controllers\RepaymentController;

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

Route::middleware('auth:sanctum')->name('repayment.')->group(function () {
    Route::post('/repayment', [RepaymentController::class, 'store'])->name('store');
    Route::get('/repayment/{id}', [RepaymentController::class, 'view'])->name('view');
    Route::put('/repayment/{id}', [RepaymentController::class, 'update'])->name('update');
    Route::delete('/repayment/{id}', [RepaymentController::class, 'delete'])->name('delete');
    Route::get('/repayments', [RepaymentController::class, 'list'])->name('list');
});