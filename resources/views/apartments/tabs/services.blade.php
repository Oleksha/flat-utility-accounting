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
        Услуги — начисления / оплаты / долг ({{ $year }})
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm align-middle text-center">
            <thead>
            <tr>
                <th class="text-start">Услуга</th>
                @for($m = 1; $m <= 12; $m++)
                    <th>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                    </th>
                @endfor
                <th>Итого долг</th>
            </tr>
            </thead>

            <tbody>
            @foreach($servicesStats as $service => $months)
                @php $totalDebt = 0; @endphp

                <tr>
                    <td class="text-start fw-semibold">{{ $service }}</td>

                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $key = sprintf('%d-%02d', $year, $m);
                            $data = $months[$key] ?? [];
                            $charged = $data['charged'] ?? 0;
                            $paid    = $data['paid'] ?? 0;
                            $debt    = $data['debt'] ?? 0;
                            $totalDebt += $debt;
                        @endphp

                        <td>
                            @if($charged || $paid)
                                <div class="small text-muted">+{{ $charged }}</div>
                                <div class="small text-success">−{{ $paid }}</div>
                                <div class="fw-bold {{ $debt > 0 ? 'text-danger' : 'text-muted' }}">
                                    {{ $debt }}
                                </div>
                            @else
                                —
                            @endif
                        </td>
                    @endfor

                    <td class="fw-bold {{ $totalDebt > 0 ? 'text-danger' : 'text-muted' }}">
                        {{ $totalDebt }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

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

