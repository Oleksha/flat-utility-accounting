<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Apartment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('apartment')->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $apartments = Apartment::all();
        return view('payments.create', compact('apartments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        Payment::create($request->all());
        return redirect()->route('payments.index')->with('success', 'Платеж добавлен');
    }

    public function edit(Payment $payment)
    {
        $apartments = Apartment::all();
        return view('payments.edit', compact('payment', 'apartments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        $payment->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Платеж обновлен');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Платеж удален');
    }
}
