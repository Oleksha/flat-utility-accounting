<div class="row mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted">Начислено</div>
                <div class="fs-4">
                    {{ number_format($totalCharges, 2, ',', ' ') }} ₽
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted">Оплачено</div>
                <div class="fs-4">
                    {{ number_format($totalPayments, 2, ',', ' ') }} ₽
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm {{ $debt > 0 ? 'border-danger' : 'border-success' }}">
            <div class="card-body">
                <div class="text-muted">Баланс</div>
                <div class="fs-4 {{ $debt > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($debt, 2, ',', ' ') }} ₽
                </div>
            </div>
        </div>
    </div>

</div>
