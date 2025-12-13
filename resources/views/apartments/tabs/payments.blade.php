<div class="d-flex justify-content-between mb-3">
    <h4>Платежи</h4>
    <div class="btn-group">
        <a href="{{ route('payments.create', ['apartment_id' => $apartment->id]) }}"
           class="btn btn-outline-success">
            ➕ Добавить один платёж
        </a>
        <a href="{{ route('payments.bulk.create', [
                    'apartment_id' => $apartment->id
                ]) }}"
           class="btn btn-success">
            💳 Платежи за месяц
        </a>
    </div>
</div>

@include('apartments.partials.payments-list')
