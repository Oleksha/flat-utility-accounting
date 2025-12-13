{{--@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Дашборд квартир</h1>

        <div class="row">
            @foreach ($apartments as $apartment)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $apartment->name }}</h5>
                            <small class="text-muted">{{ $apartment->address }}</small>
                        </div>

                        <div class="card-body">
                            <canvas id="chart-{{ $apartment->id }}" height="120"></canvas>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('apartments.show', $apartment->id) }}" class="btn btn-primary btn-sm">
                                Открыть →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            @foreach ($apartments as $apartment)
            const ctx{{ $apartment->id }} = document
                .getElementById('chart-{{ $apartment->id }}')
                .getContext('2d');

            new Chart(ctx{{ $apartment->id }}, {
                type: 'line',
                data: {
                    labels: @json($apartment->chartLabels),
                    datasets: [
                        {
                            label: 'Начисления',
                            data: @json($apartment->chartCharges),
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Платежи',
                            data: @json($apartment->chartPayments),
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
            });
            @endforeach

        });
    </script>
@endpush--}}

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Дашборд квартир — {{ $year }}</h2>

            <form method="GET">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        {{-- Общие итоги --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted">Начислено</div>
                        <div class="fs-4">{{ number_format($totalChargesAll, 2, ',', ' ') }} ₽</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted">Оплачено</div>
                        <div class="fs-4">{{ number_format($totalPaymentsAll, 2, ',', ' ') }} ₽</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card {{ $totalDebtAll > 0 ? 'border-danger' : 'border-success' }}">
                    <div class="card-body">
                        <div class="text-muted">Баланс</div>
                        <div class="fs-4 {{ $totalDebtAll > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($totalDebtAll, 2, ',', ' ') }} ₽
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Карточки квартир --}}
        <div class="row">
            @foreach($apartments as $apartment)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5>{{ $apartment->name }}</h5>
                            <p class="text-muted">{{ $apartment->address }}</p>

                            <div class="mb-2">
                                Начислено: <strong>{{ number_format($apartment->totalCharges, 2, ',', ' ') }} ₽</strong><br>
                                Оплачено: <strong>{{ number_format($apartment->totalPayments, 2, ',', ' ') }} ₽</strong><br>
                                Баланс:
                                <strong class="{{ $apartment->debt > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($apartment->debt, 2, ',', ' ') }} ₽
                                </strong>
                            </div>

                            <canvas id="chart-{{ $apartment->id }}" height="90"></canvas>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('apartments.show', $apartment) }}" class="btn btn-primary btn-sm">
                                Открыть →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @foreach($apartments as $apartment)
            new Chart(
                document.getElementById('chart-{{ $apartment->id }}'),
                {
                    type: 'line',
                    data: {
                        labels: @json($apartment->chartLabels),
                        datasets: [
                            {
                                label: 'Начисления',
                                data: @json($apartment->chartCharges),
                                borderWidth: 2,
                                tension: 0.3
                            },
                            {
                                label: 'Платежи',
                                data: @json($apartment->chartPayments),
                                borderWidth: 2,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            legend: { display: false }
                        }
                    }
                }
            );
            @endforeach

        });
    </script>
@endpush

