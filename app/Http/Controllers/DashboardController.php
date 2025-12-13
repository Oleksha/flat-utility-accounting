<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $apartments = Apartment::with(['charges', 'payments'])->get();

        foreach ($apartments as $apartment) {
            // Собираем месяцы
            $months = collect(range(0, 11))
                ->map(fn($i) => now()->subMonths($i)->format('Y-m'))
                ->reverse()
                ->values();

            // Группируем начисления
            $chargesGrouped = $apartment->charges
                ->groupBy(fn($item) => $item->period->format('Y-m'))
                ->map(fn($group) => $group->sum('amount'));

            // Группируем платежи
            $paymentsGrouped = $apartment->payments
                ->groupBy(fn($item) => $item->payment_date->format('Y-m'))
                ->map(fn($group) => $group->sum('amount'));

            // Формируем данные для графиков
            $apartment->chartLabels = $months->map(
                fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y')
            )->toArray();

            $apartment->chartCharges = $months->map(
                fn($m) => $chargesGrouped[$m] ?? 0
            )->toArray();

            $apartment->chartPayments = $months->map(
                fn($m) => $paymentsGrouped[$m] ?? 0
            )->toArray();
        }

        return view('dashboard', compact('apartments'));
    }
}
