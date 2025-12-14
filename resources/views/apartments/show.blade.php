@extends('layouts.app')

@section('content')
    <h2>Квартира: {{ $apartment->name }}</h2>
    <p class="text-muted"><strong>Адрес:</strong> {{ $apartment->address }}</p>

    <ul class="nav nav-tabs mb-4" id="apartmentTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">
                📊 Обзор
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#charges">
                📄 Начисления
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">
                💳 Платежи
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ОБЗОР --}}
        <div class="tab-pane fade show active" id="overview">
            @include('apartments.tabs.overview')
        </div>

        {{-- НАЧИСЛЕНИЯ --}}
        <div class="tab-pane fade" id="charges">
            @include('apartments.tabs.charges')
        </div>

        {{-- ПЛАТЕЖИ --}}
        <div class="tab-pane fade" id="payments">
            @include('apartments.tabs.payments')
        </div>

    </div>

    <div class="modal fade" id="uploadReceiptModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('receipts.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Загрузка квитанции</h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- ID квартиры --}}
                        <input type="hidden"
                               name="apartment_id"
                               value="{{ $apartment->id }}">

                        {{-- Период --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Месяц</label>
                                <select name="month"
                                        id="receipt-month"
                                        class="form-select"
                                        required>
                                    <option value="">— выберите —</option>
                                    @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}">
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Год</label>
                                <select name="year"
                                        id="receipt-year"
                                        class="form-select"
                                        required>
                                    <option value="">— выберите —</option>
                                    @foreach(range(now()->year, now()->year - 5) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- PDF --}}
                        <div class="mb-3">
                            <label class="form-label">PDF-квитанция</label>
                            <input type="file"
                                   name="file"
                                   accept="application/pdf"
                                   class="form-control"
                                   required>
                        </div>

                        {{-- Услуги --}}
                        <div class="mb-2 fw-bold">
                            К каким услугам относится
                        </div>

                        <div id="charges-container">
                            <div class="text-muted">
                                Выберите месяц и год
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Отмена
                        </button>

                        <button class="btn btn-primary">
                            Загрузить
                        </button>
                    </div>

                </form>

            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const apartmentId = {{ $apartment->id }};
                const monthSelect = document.getElementById('receipt-month');
                const yearSelect  = document.getElementById('receipt-year');
                const container   = document.getElementById('charges-container');

                monthSelect.addEventListener('change', () => {
                    console.log('month changed');
                    loadCharges();
                });

                yearSelect.addEventListener('change', () => {
                    console.log('year changed');
                    loadCharges();
                });


                async function loadCharges() {
                    console.log('change detected');

                    const month = monthSelect.value;
                    const year  = yearSelect.value;

                    if (!month || !year) {
                        container.innerHTML =
                            '<div class="text-muted">Выберите месяц и год</div>';
                        return;
                    }

                    container.innerHTML =
                        '<div class="text-muted">Загрузка...</div>';

                    try {
                        const response = await fetch(
                            `{{ route('charges.byPeriod') }}`
                            + `?apartment_id=${apartmentId}`
                            + `&year=${year}`
                            + `&month=${month}`
                        );

                        if (!response.ok) {
                            throw new Error('Ошибка загрузки');
                        }

                        const charges = await response.json();

                        if (!charges.length) {
                            container.innerHTML =
                                '<div class="alert alert-warning mb-0">'
                                + 'Начислений за выбранный месяц нет'
                                + '</div>';
                            return;
                        }

                        container.innerHTML = charges.map(charge => `
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="charges[]"
                           value="${charge.id}">
                    <label class="form-check-label">
                        ${charge.service.name}
                        — ${Number(charge.amount).toLocaleString('ru-RU', {
                            minimumFractionDigits: 2
                        })} ₽
                    </label>
                </div>
            `).join('');

                    } catch (e) {
                        container.innerHTML =
                            '<div class="alert alert-danger mb-0">'
                            + 'Ошибка загрузки услуг'
                            + '</div>';
                    }
                }

                monthSelect.addEventListener('change', loadCharges);
                yearSelect.addEventListener('change', loadCharges);
            });
        </script>
    </div>


@endsection
