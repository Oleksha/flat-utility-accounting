@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Платежи за месяц</h2>

        <form method="POST" action="{{ route('payments.bulk.store') }}">
            @csrf

            {{-- Верхние поля --}}
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

                <div class="col-md-4">
                    <label class="form-label">Дата платежа</label>
                    <input type="date"
                           name="payment_date"
                           class="form-control"
                           value="{{ $paymentDate }}"
                           required>
                </div>
            </div>

            {{-- Таблица услуг --}}
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Услуга</th>
                    <th width="200">Сумма платежа</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $i => $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>
                            <input type="hidden"
                                   name="payments[{{ $i }}][service]"
                                   value="{{ $service->id }}">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   name="payments[{{ $i }}][amount]"
                                   placeholder="—">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button class="btn btn-success">
                    💾 Сохранить платежи
                </button>
            </div>

        </form>
    </div>
@endsection
