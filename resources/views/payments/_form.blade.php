<div class="row">
    <div class="col-md-8 offset-md-2">

        {{-- Квартира --}}
        <div class="mb-3">
            <label class="form-label">Квартира</label>
            <select name="apartment_id" class="form-select" required>
                <option value="">Выберите...</option>
                @foreach($apartments as $apartment)
                    <option value="{{ $apartment->id }}"
                        {{ old('apartment_id', $apartmentId ?? $payment->apartment_id) == $apartment->id ? 'selected' : '' }}>
                        {{ $apartment->name }} — {{ $apartment->address }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Услуга --}}
        <div class="mb-3">
            <label class="form-label">Услуга (необязательно)</label>
            <select name="service_id" class="form-select">
                <option value="">—</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}"
                        {{ old('service_id', $payment->service_id) == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Дата платежа --}}
        <div class="mb-3">
            <label class="form-label">Дата платежа</label>
            <input type="date"
                   name="payment_date"
                   class="form-control"
                   value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                   required>
        </div>

        {{-- Сумма --}}
        <div class="mb-3">
            <label class="form-label">Сумма</label>
            <input type="number"
                   step="0.01"
                   min="0"
                   name="amount"
                   class="form-control"
                   value="{{ old('amount', $payment->amount) }}"
                   required>
        </div>

        {{-- Комментарий --}}
        <div class="mb-3">
            <label class="form-label">Комментарий</label>
            <textarea name="comment"
                      class="form-control"
                      rows="2">{{ old('comment', $payment->comment) }}</textarea>
        </div>

        <div class="text-end">
            <button class="btn btn-success">
                {{ $payment->exists ? 'Сохранить изменения' : 'Добавить платёж' }}
            </button>
        </div>

    </div>
</div>
