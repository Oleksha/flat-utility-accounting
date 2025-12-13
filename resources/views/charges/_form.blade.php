<div class="row">
    <div class="col-md-8 offset-md-2">

        {{-- Квартира --}}
        <div class="mb-3">
            <label class="form-label">Квартира</label>
            <select name="apartment_id" class="form-select" required>
                <option value="">Выберите...</option>
                @foreach($apartments as $apartment)
                    <option value="{{ $apartment->id }}"
                        {{ old('apartment_id', $charge->apartment_id) == $apartment->id ? 'selected' : '' }}>
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
        <div class="mb-3">
            <label class="form-label">Период</label>
            <input type="month"
                   name="period"
                   value="{{ old('period', optional($charge->period)->format('Y-m')) }}"
                   class="form-control"
                   required>
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
