<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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
            'comment'      => 'nullable|string|max:255',
            'receipt'      => 'nullable|file|mimes:pdf|max:5120', // до 5 МБ
        ]);

        $data['period'] = \Carbon\Carbon::create(
            $data['year'],
            $data['month'],
            1
        );

        // убрать month/year
        unset($data['month'], $data['year']);

        if ($request->hasFile('receipt')) {

            $year  = $data['period']->year;
            $month = sprintf('%02d', $data['period']->month);

            $data['receipt_path'] = $request->file('receipt')
                ->store("receipts/{$year}/{$month}", 'public');
        }

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
            'receipt'      => 'nullable|file|mimes:pdf|max:5120', // до 5 МБ
        ]);

        $data['period'] = \Carbon\Carbon::create(
            $data['year'],
            $data['month'],
            1
        );

        // убрать month/year
        unset($data['month'], $data['year']);

        if ($request->hasFile('receipt')) {

            // удалить старый файл
            if ($charge->receipt_path) {
                Storage::disk('public')->delete($charge->receipt_path);
            }

            $year  = $data['period']->year;
            $month = sprintf('%02d', $data['period']->month);

            $data['receipt_path'] = $request->file('receipt')
                ->store("receipts/{$year}/{$month}", 'public');
        }

        $charge->update($data);

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Начисление обновлено');
    }

    public function destroy(Charge $charge)
    {
        $apartmentId = $charge->apartment_id;

        if ($charge->receipt_path) {
            Storage::disk('public')->delete($charge->receipt_path);
        }

        $charge->delete();

        return redirect()
            ->route('apartments.show', $apartmentId)
            ->with('success', 'Начисление удалено');
    }

    public function copyFromPrevious(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer',
        ]);

        $currentPeriod = Carbon::create($data['year'], $data['month'], 1);
        $prevPeriod = $currentPeriod->copy()->subMonth();

        // начисления прошлого месяца
        $previousCharges = Charge::where('apartment_id', $data['apartment_id'])
            ->whereDate('period', $prevPeriod)
            ->get();

        if ($previousCharges->isEmpty()) {
            return back()->with('warning', 'Нет начислений за прошлый месяц');
        }

        // услуги, которые уже есть в текущем месяце
        $existingServices = Charge::where('apartment_id', $data['apartment_id'])
            ->whereDate('period', $currentPeriod)
            ->pluck('service_id')
            ->toArray();

        $copied = 0;

        foreach ($previousCharges as $charge) {

            if (in_array($charge->service_id, $existingServices)) {
                continue;
            }

            Charge::create([
                'apartment_id' => $charge->apartment_id,
                'service_id'   => $charge->service_id,
                'amount'       => $charge->amount,
                'period'       => $currentPeriod,
            ]);

            $copied++;
        }

        return back()->with(
            'success',
            "Скопировано начислений: {$copied}"
        );
    }

    public function bulkCreate(Request $request)
    {
        return view('charges.bulk-create', [
            'services'    => Service::all(),
            'apartments'  => Apartment::all(),
            'month'       => $request->get('month', now()->month),
            'year'        => $request->get('year', now()->year),
            'apartmentId' => $request->get('apartment_id'),
        ]);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'apartment_id'      => 'required|exists:apartments,id',
            'month'             => 'required|integer|min:1|max:12',
            'year'              => 'required|integer',
            'charges'           => 'required|array',
            'charges.*.service' => 'required|exists:services,id',
            'charges.*.amount'  => 'nullable|numeric|min:0',
        ]);

        $period = Carbon::create($data['year'], $data['month'], 1);

        foreach ($data['charges'] as $charge) {

            if ($charge['amount'] === null || $charge['amount'] == 0) {
                continue;
            }

            // защита от дублей
            $exists = Charge::where([
                'apartment_id' => $data['apartment_id'],
                'service_id'   => $charge['service'],
                'period'       => $period,
            ])->exists();

            if ($exists) {
                continue;
            }

            Charge::create([
                'apartment_id' => $data['apartment_id'],
                'service_id'   => $charge['service'],
                'amount'       => $charge['amount'],
                'period'       => $period,
            ]);
        }

        return redirect()
            ->route('apartments.show', $data['apartment_id'])
            ->with('success', 'Начисления сохранены');
    }

}
