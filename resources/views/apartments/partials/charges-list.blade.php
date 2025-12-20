@forelse($charges as $month => $items)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>
                    {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
                </strong>
            </div>
            <span class="badge bg-secondary">
                Итого: {{ number_format($items->sum('amount'), 2, ',', ' ') }} ₽
            </span>
        </div>
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
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#receiptsModal-{{ $charge->id }}">
                                    📄 {{ $charge->receipts->count() }}
                                </button>
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
        @foreach($items as $charge)
            <div class="modal fade"
                 id="receiptsModal-{{ $charge->id }}"
                 tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Квитанции — {{ $charge->service->name }}
                                ({{ $charge->period->translatedFormat('F Y') }})
                            </h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            @if($charge->receipts->isEmpty())
                                <div class="alert alert-secondary mb-0">
                                    Квитанций нет
                                </div>
                            @else
                                <table class="table table-sm align-middle">
                                    <thead>
                                    <tr>
                                        <th>Файл</th>
                                        <th>Период</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($charge->receipts as $receipt)
                                        <tr>
                                            <td>
                                                📄 {{ $receipt->original_name ?? 'Квитанция.pdf' }}
                                            </td>
                                            <td>
                                                {{ $receipt->period->format('m.Y') }}
                                            </td>
                                            <td class="text-end">

                                                <a href="{{ route('receipts.download', $receipt) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    Открыть
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('receipts.destroy', $receipt) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Удалить квитанцию?')">
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
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@empty
    <p class="text-muted">Начислений за {{ $year }} год нет.</p>
@endforelse
