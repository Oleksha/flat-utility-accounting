@extends('layouts.app')

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
@endpush
