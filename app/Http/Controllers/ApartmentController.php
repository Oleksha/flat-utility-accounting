<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        $charges = $apartment->charges()
            ->with('service', 'receipts')
            ->whereYear('period', $year)
            ->orderBy('period')
            ->get()
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

        $chargesForYear = $apartment->charges()
            ->whereYear('period', $year)
            ->with('service')
            ->orderBy('period')
            ->get();

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
            'paymentsGraph',
            'chargesForYear'
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

    public function pdfReport(Apartment $apartment)
    {
        $year = request()->get('year', now()->year);

        $charges = $apartment->charges()
            ->whereYear('period', $year)
            ->get()
            ->groupBy(fn($c) => $c->period->format('Y-m'));

        $payments = $apartment->payments()
            ->whereYear('payment_date', $year)
            ->get()
            ->groupBy(fn($p) => $p->payment_date->format('Y-m'));

        // месяцы года
        $months = collect(range(1, 12))
            ->map(fn($m) => sprintf('%d-%02d', $year, $m));

        // сводка по месяцам
        $rows = $months->map(function ($m) use ($charges, $payments) {
            $charged = ($charges[$m] ?? collect())->sum('amount');
            $paid    = ($payments[$m] ?? collect())->sum('amount');

            return [
                'month'   => $m,
                'charged' => $charged,
                'paid'    => $paid,
                'balance' => $charged - $paid,
            ];
        });

        $totalCharged = $rows->sum('charged');
        $totalPaid    = $rows->sum('paid');
        $totalBalance = $totalCharged - $totalPaid;

        $pdf = Pdf::loadView('apartments.reports.year', [
            'apartment'     => $apartment,
            'year'          => $year,
            'rows'          => $rows,
            'totalCharged'  => $totalCharged,
            'totalPaid'     => $totalPaid,
            'totalBalance'  => $totalBalance,
        ])->setPaper('a4');

        return $pdf->download("Отчет_{$apartment->name}_{$year}.pdf");
    }
}
