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
            <div class="modal fade" id="receiptsModal-{{ $charge->id }}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Квитанции — {{ $charge->service->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body d-flex" style="height:70vh">

                            {{-- Список квитанций --}}
                            <div class="list-group me-3 receipts-list" style="width:280px">
                                @foreach($charge->receipts as $receipt)
                                    <button
                                        type="button"
                                        class="list-group-item list-group-item-action receipt-item"
                                        data-pdf="{{ Storage::url($receipt->file_path) }}">
                                        {{ $receipt->created_at->format('d.m.Y') }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Просмотр PDF --}}
                            <div class="flex-fill border rounded">
                                <iframe class="receipt-preview w-100 h-100"
                                        style="border:0"></iframe>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
@empty
    <p class="text-muted">Начислений за {{ $year }} год нет.</p>
@endforelse

