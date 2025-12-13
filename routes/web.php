<?php

use App\Http\Controllers;

Route::get('/', [Controllers\DashboardController::class, 'index'])->name('dashboard');

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
