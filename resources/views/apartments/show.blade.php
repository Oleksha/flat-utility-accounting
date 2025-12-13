@extends('layouts.app')

@section('content')
    <h1>Квартира: {{ $apartment->name }}</h1>
    <p><strong>Адрес:</strong> {{ $apartment->address }}</p>

    <div class="container">

        {{--<div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Данные за {{ $year }} год</h4>

                <form method="GET" action="{{ route('apartments.show', $apartment->id) }}" class="d-flex">
                    <select name="year" class="form-select me-2" onchange="this.form.submit()">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <noscript>
                        <button class="btn btn-primary">Показать</button>
                    </noscript>
                </form>

            </div>
        </div>--}}
        @php
            $prevYear = $year - 1;
            $nextYear = $year + 1;
        @endphp

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('apartments.show', ['apartment' => $apartment->id, 'year' => $prevYear]) }}"
               class="btn btn-outline-secondary">
                ⟵ {{ $prevYear }}
            </a>

            <div class="fw-bold fs-5">{{ $year }} год</div>

            <a href="{{ route('apartments.show', ['apartment' => $apartment->id, 'year' => $nextYear]) }}"
               class="btn btn-outline-secondary">
                {{ $nextYear }} ⟶
            </a>
        </div>


        <!-- Баланс -->
    {{--<div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Баланс</div>
                <div class="card-body">
                    <h3 class="card-title">
                        {{ $apartment->charges->sum('amount') - $apartment->payments->sum('amount') }} ₽
                    </h3>
                    <p class="card-text">Начисления минус платежи</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Начисления
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addChargeModal">+</button>
                </div>
                <div class="card-body">
                    <h3 class="card-title">{{ $apartment->charges->sum('amount') }} ₽</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Платежи
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPaymentModal">+</button>
                </div>
                <div class="card-body">
                    <h3 class="card-title">{{ $apartment->payments->sum('amount') }} ₽</h3>
                </div>
            </div>
        </div>
    </div>--}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-bg-light">
                    <div class="card-body">
                        <h6>Начислено</h6>
                        <div class="fs-4">{{ number_format($totalCharges, 2, ',', ' ') }} ₽</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-light">
                    <div class="card-body">
                        <h6>Оплачено</h6>
                        <div class="fs-4">{{ number_format($totalPayments, 2, ',', ' ') }} ₽</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card {{ $debt > 0 ? 'text-bg-danger' : 'text-bg-success' }}">
                    <div class="card-body">
                        <h6>Долг</h6>
                        <div class="fs-4">{{ number_format($debt, 2, ',', ' ') }} ₽</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">График начислений и платежей за {{ $year }} год</h5>
                <canvas id="yearChart" height="100"></canvas>
            </div>
        </div>


        <!-- Начисления -->
    <h4 class="mt-4">Начисления</h4>
    @forelse ($charges as $month => $items)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</strong>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($items as $charge)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $charge->service->name }}</span>
                        <span>{{ number_format($charge->amount, 2, ',', ' ') }} ₽</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-muted">Нет данных за {{ $year }} год.</p>
    @endforelse

    <!-- Платежи -->
    <h4 class="mt-4">Платежи</h4>
    @forelse ($payments as $month => $items)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</strong>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($items as $payment)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $payment->service->name ?? 'Платеж' }}</span>
                        <span>{{ number_format($payment->amount, 2, ',', ' ') }} ₽</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-muted">Нет данных за {{ $year }} год.</p>
    @endforelse


        <a class="btn btn-primary mt-3" href="{{ route('apartments.index') }}">Назад</a>

    <!-- Модальное окно для начисления -->
    <div class="modal fade" id="addChargeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('charges.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить начисление</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Услуга</label>
                            <select name="service_id" class="form-select" required>
                                @foreach(\App\Models\Service::all() as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Сумма</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Период</label>
                            <input type="month" name="period" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Комментарий</label>
                            <input type="text" name="comment" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Сохранить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно для платежа -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить платеж</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Сумма</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Дата</label>
                            <input type="date" name="payment_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Комментарий</label>
                            <input type="text" name="comment" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Сохранить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {

                    const ctx = document.getElementById('yearChart').getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($labels),
                            datasets: [
                                {
                                    label: 'Начисления',
                                    data: @json($chargesGraph),
                                    borderWidth: 2,
                                    tension: 0.3
                                },
                                {
                                    label: 'Платежи',
                                    data: @json($paymentsGraph),
                                    borderWidth: 2,
                                    tension: 0.3
                                }
                            ]
                        }
                    });
                });
            </script>
    @endpush
