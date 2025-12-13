<div class="d-flex justify-content-between mb-3">
    <h4>Начисления</h4>
    <a href="{{ route('charges.create', ['apartment_id' => $apartment->id]) }}"
       class="btn btn-primary">
        ➕ Добавить начисление
    </a>
</div>

@include('apartments.partials.charges-list')
