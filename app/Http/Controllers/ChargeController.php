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

    public function create(Request $request)
    {
        return view('charges.create', [
            'apartments' => Apartment::all(),
            'services'   => Service::all(),
            'charge'     => new Charge(),
            'apartmentId'=> $request->get('apartment_id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'service_id'   => 'required|exists:services,id',
            'amount'       => 'required|numeric|min:0',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer',
            'comment' => 'nullable|string|max:255',
        ]);

        $data['period'] = \Carbon\Carbon::create(
            $data['year'],
            $data['month'],
            1
        );

        // убрать month/year
        unset($data['month'], $data['year']);

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
            'service_id'   => 'required|exists:services,id',
            'amount'       => 'required|numeric|min:0',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer',
            'comment'      => 'nullable|string|max:255',
        ]);

        $data['period'] = \Carbon\Carbon::create(
            $data['year'],
            $data['month'],
            1
        );

        // убрать month/year
        unset($data['month'], $data['year']);

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
