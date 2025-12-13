<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Начисления и платежи за {{ $year }} год</h5>
        <canvas id="yearChart" height="110"></canvas>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const ctx = document.getElementById('yearChart');
            if (!ctx) return;

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
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
@endpush
