<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all();
        return view('apartments.index', compact('apartments'));
    }

    public function create()
    {
        return view('apartments.create-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Apartment::create($request->all());

        return redirect()->route('apartments.index')->with('success', 'Квартира добавлена');
    }

    /*public function show(Apartment $apartment)
    {
        // Определяем выбранный год (по умолчанию — текущий)
        $year = request()->get('year', now()->year);

        // Все уникальные года, в которых есть начисления или платежи
        $years = collect()
            ->merge($apartment->charges->pluck('period')->map->year)
            ->merge($apartment->payments->pluck('payment_date')->map->year)
            ->unique()
            ->sortDesc()
            ->values();

        // Фильтруем начисления по году
        $charges = $apartment->charges
            ->filter(fn($c) => $c->period->year == $year)
            ->sortByDesc('period')
            ->groupBy(fn($c) => $c->period->format('Y-m'));

        // Фильтруем платежи по году
        $payments = $apartment->payments
            ->filter(fn($p) => $p->payment_date->year == $year)
            ->sortByDesc('payment_date')
            ->groupBy(fn($p) => $p->payment_date->format('Y-m'));

        return view('apartments.show', compact(
            'apartment',
            'charges',
            'payments',
            'years',
            'year'
        ));
    }*/
    public function show(Apartment $apartment)
    {
        // выбранный год
        $year = (int) request()->get('year', now()->year);

        // список всех годов
        $years = collect()
            ->merge($apartment->charges->pluck('period')->map->year)
            ->merge($apartment->payments->pluck('payment_date')->map->year)
            ->unique()
            ->sortDesc()
            ->values();

        // начисления за год
        $charges = $apartment->charges
            ->filter(fn($c) => $c->period->year == $year)
            ->sortBy('period')
            ->groupBy(fn($c) => $c->period->format('Y-m'));

        // платежи за год
        $payments = $apartment->payments
            ->filter(fn($p) => $p->payment_date->year == $year)
            ->sortBy('payment_date')
            ->groupBy(fn($p) => $p->payment_date->format('Y-m'));

        // Итоги за год
        $totalCharges = $charges->flatten()->sum('amount');
        $totalPayments = $payments->flatten()->sum('amount');
        $debt = $totalCharges - $totalPayments;

        // данные для графика
        $labels = collect(range(1, 12))
            ->map(fn($m) => sprintf('%02d', $m))
            ->map(fn($m) => "$year-$m")  // 2025-01, 2025-02
            ->toArray();

        $chargesGraph = collect($labels)
            ->map(fn($m) => ($charges[$m] ?? collect())->sum('amount'))
            ->toArray();

        $paymentsGraph = collect($labels)
            ->map(fn($m) => ($payments[$m] ?? collect())->sum('amount'))
            ->toArray();

        return view('apartments.show', compact(
            'apartment',
            'charges',
            'payments',
            'years',
            'year',
            'totalCharges',
            'totalPayments',
            'debt',
            'labels',
            'chargesGraph',
            'paymentsGraph'
        ));
    }

    public function edit(Apartment $apartment)
    {
        return view('apartments.create-edit', compact('apartment'));
    }

    public function update(Request $request, Apartment $apartment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $apartment->update($request->all());

        return redirect()->route('apartments.index')->with('success', 'Квартира обновлена');
    }

    public function destroy(Apartment $apartment)
    {
        $apartment->delete();
        return redirect()->route('apartments.index')->with('success', 'Квартира удалена');
    }
}
