<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        $apartments = Apartment::with(['charges', 'payments'])->get();

        $totalChargesAll = 0;
        $totalPaymentsAll = 0;

        foreach ($apartments as $apartment) {

            $charges = $apartment->charges
                ->filter(fn($c) => $c->period->year == $year);

            $payments = $apartment->payments
                ->filter(fn($p) => $p->payment_date->year == $year);

            $apartment->totalCharges = $charges->sum('amount');
            $apartment->totalPayments = $payments->sum('amount');
            $apartment->debt = $apartment->totalCharges - $apartment->totalPayments;

            $totalChargesAll += $apartment->totalCharges;
            $totalPaymentsAll += $apartment->totalPayments;

            // данные для мини-графика
            $months = collect(range(1, 12))
                ->map(fn($m) => sprintf('%02d', $m))
                ->map(fn($m) => "$year-$m");

            $chargesByMonth = $charges
                ->groupBy(fn($c) => $c->period->format('Y-m'));

            $paymentsByMonth = $payments
                ->groupBy(fn($p) => $p->payment_date->format('Y-m'));

            $apartment->chartLabels = $months
                ->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M'))
                ->toArray();

            $apartment->chartCharges = $months
                ->map(fn($m) => ($chargesByMonth[$m] ?? collect())->sum('amount'))
                ->toArray();

            $apartment->chartPayments = $months
                ->map(fn($m) => ($paymentsByMonth[$m] ?? collect())->sum('amount'))
                ->toArray();
        }

        $totalDebtAll = $totalChargesAll - $totalPaymentsAll;

        return view('dashboard', compact(
            'apartments',
            'year',
            'totalChargesAll',
            'totalPaymentsAll',
            'totalDebtAll'
        ));
    }
    /*public function index()
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
    }*/
}
