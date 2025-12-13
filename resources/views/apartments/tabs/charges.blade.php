<div class="d-flex justify-content-between mb-3">
    <h4>Начисления</h4>
    <div class="btn-group">
        <a href="{{ route('charges.create', [
            'apartment_id' => $apartment->id
        ]) }}"
           class="btn btn-outline-primary">
            ➕ Добавить одно начисление
        </a>

        <a href="{{ route('charges.bulk.create', [
            'apartment_id' => $apartment->id,
            'month' => $year ? now()->month : now()->month,
            'year'  => $year
        ]) }}"
           class="btn btn-primary">
            📋 Начисления за месяц
        </a>
    </div>
</div>

@include('apartments.partials.charges-list')
