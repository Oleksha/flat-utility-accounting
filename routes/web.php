<?php

use App\Http\Controllers;

Route::get('/', [Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/charges/by-period', [Controllers\ChargeController::class, 'byPeriod'])
    ->name('charges.byPeriod');

Route::resource('apartments', Controllers\ApartmentController::class);
Route::resource('providers', Controllers\ProviderController::class);
Route::resource('services', Controllers\ServiceController::class);
Route::resource('rates', Controllers\RateController::class);
Route::resource('charges', Controllers\ChargeController::class);
Route::resource('payments', Controllers\PaymentController::class);

Route::get(
    '/apartments/{apartment}/report/pdf',
    [Controllers\ApartmentController::class, 'pdfReport']
)->name('apartments.report.pdf');

// Кнопка скопировать начисления предыдущего месяца
Route::post(
    '/charges/copy-from-previous',
    [Controllers\ChargeController::class, 'copyFromPrevious']
)->name('charges.copyFromPrevious');

// Массовое добавление начислений на месяц
Route::get('/charges/bulk/create', [Controllers\ChargeController::class, 'bulkCreate'])
    ->name('charges.bulk.create');

Route::post('/charges/bulk/store', [Controllers\ChargeController::class, 'bulkStore'])
    ->name('charges.bulk.store');

// Массовое добавление оплат на месяц
Route::get('/payments/bulk/create', [Controllers\PaymentController::class, 'bulkCreate'])
    ->name('payments.bulk.create');

Route::post('/payments/bulk/store', [Controllers\PaymentController::class, 'bulkStore'])
    ->name('payments.bulk.store');

// Квитанции
Route::post('/receipts', [Controllers\ReceiptController::class, 'store'])
    ->name('receipts.store');

Route::delete('/receipts/{receipt}', [Controllers\ReceiptController::class, 'destroy'])
    ->name('receipts.destroy');

Route::get('/receipts/{receipt}/download', [Controllers\ReceiptController::class, 'download'])
    ->name('receipts.download');
