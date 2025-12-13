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
        $apartments = Apartment::all();
        $services = Service::all();
        return view('charges.create', [
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
            'charge'     => new Charge(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        Charge::create($request->all());
        return redirect()->route('charges.index')->with('success', 'Начисление добавлено');
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
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ]);

        $charge->update($request->all());
        return redirect()->route('charges.index')->with('success', 'Начисление обновлено');
    }

    public function destroy(Charge $charge)
    {
        $charge->delete();
        return redirect()->route('charges.index')->with('success', 'Начисление удалено');
    }
}
