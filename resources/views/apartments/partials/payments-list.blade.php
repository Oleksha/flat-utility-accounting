@forelse($payments as $month => $items)

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>
                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
            </strong>

            <span class="badge bg-success">
                Оплачено: {{ number_format($items->sum('amount'), 2, ',', ' ') }} ₽
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Дата</th>
                    <th>Услуга</th>
                    <th>Комментарий</th>
                    <th class="text-end">Сумма</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $payment)
                    <tr>
                        <td>
                            {{ $payment->payment_date->format('d.m.Y') }}
                        </td>
                        <td>
                            {{ $payment->service->name ?? '—' }}
                        </td>
                        <td class="text-muted">
                            {{ $payment->comment ?? '—' }}
                        </td>
                        <td class="text-end">
                            {{ number_format($payment->amount, 2, ',', ' ') }} ₽
                        </td>
                        <td class="text-end">

                            <a href="{{ route('payments.edit', $payment) }}"
                               class="btn btn-sm btn-outline-primary">
                                ✏️
                            </a>

                            <form action="{{ route('payments.destroy', $payment) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Удалить платёж?')">
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
    <p class="text-muted">Платежей за {{ $year }} год нет.</p>
@endforelse
