<div class="row">
    <div class="col-md-8 offset-md-2">

        {{-- Квартира --}}
        <div class="mb-3">
            <label class="form-label">Квартира</label>
            <select name="apartment_id" class="form-select" required>
                <option value="">Выберите...</option>
                @foreach($apartments as $apartment)
                    <option value="{{ $apartment->id }}"
                        {{ old('apartment_id', $apartmentId ?? $charge->apartment_id) == $apartment->id ? 'selected' : '' }}>
                        {{ $apartment->name }} — {{ $apartment->address }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Услуга --}}
        <div class="mb-3">
            <label class="form-label">Услуга</label>
            <select name="service_id" class="form-select" required>
                <option value="">Выберите...</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}"
                        {{ old('service_id', $charge->service_id) == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Период --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Месяц</label>
                <select name="month" class="form-select" required>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}"
                            {{ old('month', optional($charge->period)->month ?? now()->month) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Год</label>
                <select name="year" class="form-select" required>
                    @foreach(range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}"
                            {{ old('year', optional($charge->period)->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- PDF-квитанция --}}
        <div class="mb-3">
            <label class="form-label">PDF-квитанция</label>

            <input type="file"
                   name="receipt"
                   class="form-control"
                   accept="application/pdf">

            @if($charge->receipt_path)
                <div class="mt-2">
                    <a href="{{ Storage::url($charge->receipt_path) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-secondary">
                        📄 Открыть квитанцию
                    </a>
                </div>
            @endif
        </div>

        {{-- Сумма --}}
        <div class="mb-3">
            <label class="form-label">Сумма (₽)</label>
            <input type="number"
                   name="amount"
                   class="form-control"
                   step="0.01"
                   min="0"
                   value="{{ old('amount', $charge->amount) }}"
                   required>
        </div>

        {{-- Комментарий --}}
        <div class="mb-3">
            <label class="form-label">Комментарий</label>
            <textarea name="comment" class="form-control" rows="2">{{ old('comment', $charge->comment) }}</textarea>
        </div>

        {{-- Кнопка --}}
        <div class="text-end">
            <button class="btn btn-primary">
                {{ $charge->exists ? 'Сохранить изменения' : 'Добавить начисление' }}
            </button>
        </div>

    </div>
</div>
