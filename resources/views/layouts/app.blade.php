<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учет платежей за квартиры</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Дашбоард</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('apartments.index') }}">Квартиры</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('providers.index') }}">Поставщики</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Услуги</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('rates.index') }}">Тарифы</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('charges.index') }}">Начисления</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}">Платежи</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
<script>
    document.addEventListener('shown.bs.modal', function (event) {
        const modal = event.target;

        const list = modal.querySelector('.receipts-list');
        const iframe = modal.querySelector('.receipt-preview');

        if (!list || !iframe) return;

        const items = list.querySelectorAll('.receipt-item');
        if (!items.length) return;

        // 🟢 Сброс подсветки
        items.forEach(i => i.classList.remove('active'));

        // 🟢 Автооткрытие первой квитанции
        const first = items[0];
        first.classList.add('active');
        iframe.src = first.dataset.pdf;

        // 🟢 Клик по квитанции
        items.forEach(item => {
            item.addEventListener('click', () => {
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                iframe.src = item.dataset.pdf;
            });
        });
    });
</script>
</body>
</html>
