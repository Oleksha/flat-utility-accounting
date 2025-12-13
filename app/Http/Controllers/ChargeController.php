<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function index()
    {
        $charges = Charge::with(['apartment', 'service'])->get();
        return view('charges.index', compact('charges'));
    }

    public function create()
    {
        return view('charges.create', [
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
            'charge'     => new Charge(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        Charge::create($data);

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Начисление добавлено');
    }

    public function edit(Charge $charge)
    {
        return view('charges.edit', [
            'charge'     => $charge,
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
        ]);
    }

    public function update(Request $request, Charge $charge)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        $charge->update($data);

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Начисление обновлено');
    }

    public function destroy(Charge $charge)
    {
        $apartmentId = $charge->apartment_id;
        $charge->delete();

        return redirect()
            ->route('apartments.show', $apartmentId)
            ->with('success', 'Начисление удалено');
    }
}
