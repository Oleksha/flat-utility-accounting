<div class="card mb-4">
    <div class="card-header">
        Начисления по услугам за {{ $year }}
    </div>
    <div class="card-body">
        <canvas id="servicesChart" height="120"></canvas>
    </div>
</div>
<script>
    document.addEventListener('shown.bs.tab', function (e) {
        if (e.target.dataset.bsTarget !== '#services') return;

        if (window.servicesChartInitialized) return;
        window.servicesChartInitialized = true;

        new Chart(document.getElementById('servicesChart'), {
            type: 'bar',
            data: {
                labels: @json($servicesTotals->keys()),
                datasets: [{
                    label: 'Начислено',
                    data: @json($servicesTotals->values()),
                }]
            }
        });
    });
</script>
<div class="card">
    <div class="card-header">
        Начисления по месяцам
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm text-center align-middle">
            <thead>
            <tr>
                <th class="text-start">Услуга</th>
                @for($m = 1; $m <= 12; $m++)
                    <th>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                    </th>
                @endfor
                <th>Итого</th>
            </tr>
            </thead>

            <tbody>
            @foreach($servicesMatrix as $service => $months)
                <tr>
                    <td class="text-start">{{ $service }}</td>

                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $key = sprintf('%d-%02d', $year, $m);
                            $value = $months[$key] ?? 0;
                        @endphp
                        <td>{{ $value ?: '—' }}</td>
                    @endfor

                    <td class="fw-bold">
                        {{ number_format($months->sum(), 2, '.', ' ') }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

