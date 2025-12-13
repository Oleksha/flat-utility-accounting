<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('apartment')->get();
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        return view('payments.create', [
            'payment'    => new Payment(),
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
            'apartmentId'=> $request->get('apartment_id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id'   => 'nullable|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        Payment::create($data);

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Платёж добавлен');
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', [
            'payment'    => $payment,
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id'   => 'nullable|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        $payment->update($data);

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Платёж обновлён');
    }

    public function destroy(Payment $payment)
    {
        $apartmentId = $payment->apartment_id;
        $payment->delete();

        return redirect()
            ->route('apartments.show', $apartmentId)
            ->with('success', 'Платёж удалён');
    }

    public function bulkCreate(Request $request)
    {
        return view('payments.bulk-create', [
            'apartments'  => Apartment::all(),
            'services'    => Service::all(),
            'month'       => $request->get('month', now()->month),
            'year'        => $request->get('year', now()->year),
            'apartmentId' => $request->get('apartment_id'),
            'paymentDate' => now()->format('Y-m-d'),
        ]);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'payment_date' => 'required|date',
            'payments'     => 'required|array',
            'payments.*.service' => 'nullable|exists:services,id',
            'payments.*.amount'  => 'nullable|numeric|min:0',
        ]);

        foreach ($data['payments'] as $payment) {

            if (empty($payment['amount']) || $payment['amount'] == 0) {
                continue;
            }

            Payment::create([
                'apartment_id' => $data['apartment_id'],
                'service_id'   => $payment['service'],
                'payment_date' => $data['payment_date'],
                'amount'       => $payment['amount'],
            ]);
        }

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Платежи сохранены');
    }

}
