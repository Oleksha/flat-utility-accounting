@forelse($charges as $month => $items)

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>
                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
            </strong>

            <span class="badge bg-secondary">
                Итого: {{ number_format($items->sum('amount'), 2, ',', ' ') }} ₽
            </span>
        </div>

        {{--<table class="table table-bordered">
            <thead>
            <tr>
                <th>Услуга</th>
                <th>Сумма</th>
                <th>Период</th>
                <th>Квитанции</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $charge)
                <tr>
                    <td>{{ $charge->service->name }}</td>
                    <td>{{ number_format($charge->amount, 2, ',', ' ') }} ₽</td>
                    <td>{{ $charge->period->translatedFormat('F Y') }}</td>
                    <td>
                        @if($charge->receipts->isEmpty())
                            <span class="text-muted">Нет</span>
                        @else
                            @foreach($charge->receipts as $receipt)
                                <a href="{{ route('receipts.download', $receipt) }}"
                                   class="btn btn-sm btn-outline-primary mb-1"
                                   target="_blank">
                                    📄 {{ $receipt->period->format('m.Y') }}
                                </a>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>--}}

        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Услуга</th>
                    <th>Комментарий</th>
                    <th class="text-end">Сумма</th>
                    <th class="text-end">Квитанции</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $charge)
                    <tr>
                        <td>{{ $charge->service->name }}</td>
                        <td class="text-muted">
                            {{ $charge->comment ?? '—' }}
                        </td>
                        <td class="text-end {{ $charge->amount > 5000 ? 'fw-bold text-danger' : '' }}">
                            {{ number_format($charge->amount, 2, ',', ' ') }} ₽
                        </td>
                        <td class="text-end">
                            @if($charge->receipts->isEmpty())
                                <span class="text-muted">Нет</span>
                            @else
                                @foreach($charge->receipts as $receipt)
                                    <a href="{{ route('receipts.download', $receipt) }}"
                                       class="btn btn-sm btn-outline-primary mb-1"
                                       target="_blank">
                                        📄 {{ $receipt->period->format('m.Y') }}
                                    </a>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-end">

                            <a href="{{ route('charges.edit', $charge) }}"
                               class="btn btn-sm btn-outline-primary">
                                ✏️
                            </a>

                            <form action="{{ route('charges.destroy', $charge) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Удалить начисление?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    🗑
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@empty
    <p class="text-muted">Начислений за {{ $year }} год нет.</p>
@endforelse
