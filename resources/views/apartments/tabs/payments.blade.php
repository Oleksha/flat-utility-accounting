<div class="d-flex justify-content-between mb-3">
    <h4>Платежи</h4>
    <a href="{{ route('payments.create', ['apartment_id' => $apartment->id]) }}"
       class="btn btn-success">
        ➕ Добавить платёж
    </a>
</div>

@include('apartments.partials.payments-list')
