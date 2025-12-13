<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Apartment;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index()
    {
        $rates = Rate::with(['apartment', 'provider', 'service'])->get();
        return view('rates.index', compact('rates'));
    }

    public function create()
    {
        $apartments = Apartment::all();
        $providers = Provider::all();
        $services = Service::all();
        return view('rates.create', compact('apartments', 'providers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,metered',
            'start_date' => 'required|date',
        ]);

        Rate::create($request->all());
        return redirect()->route('rates.index')->with('success', 'Тариф добавлен');
    }

    public function show(Rate $rate)
    {
        return view('rates.show', compact('rate'));
    }

    public function edit(Rate $rate)
    {
        $apartments = Apartment::all();
        $providers = Provider::all();
        $services = Service::all();
        return view('rates.edit', compact('rate', 'apartments', 'providers', 'services'));
    }

    public function update(Request $request, Rate $rate)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,metered',
            'start_date' => 'required|date',
        ]);

        $rate->update($request->all());
        return redirect()->route('rates.index')->with('success', 'Тариф обновлен');
    }

    public function destroy(Rate $rate)
    {
        $rate->delete();
        return redirect()->route('rates.index')->with('success', 'Тариф удален');
    }
}
