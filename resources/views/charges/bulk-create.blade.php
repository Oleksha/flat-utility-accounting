@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Начисления за месяц</h2>

        <form method="POST" action="{{ route('charges.bulk.store') }}">
            @csrf

            {{-- Квартира --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Квартира</label>
                    <select name="apartment_id" class="form-select" required>
                        @foreach($apartments as $apartment)
                            <option value="{{ $apartment->id }}"
                                {{ $apartmentId == $apartment->id ? 'selected' : '' }}>
                                {{ $apartment->name }} — {{ $apartment->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Месяц --}}
                <div class="col-md-4">
                    <label class="form-label">Месяц</label>
                    <select name="month" class="form-select">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Год --}}
                <div class="col-md-4">
                    <label class="form-label">Год</label>
                    <select name="year" class="form-select">
                        @foreach(range(now()->year, now()->year - 5) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Таблица услуг --}}
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Услуга</th>
                    <th width="200">Сумма</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $i => $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>
                            <input type="hidden"
                                   name="charges[{{ $i }}][service]"
                                   value="{{ $service->id }}">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   name="charges[{{ $i }}][amount]"
                                   placeholder="—">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button class="btn btn-success">
                    💾 Сохранить начисления
                </button>
            </div>
        </form>
    </div>
@endsection
