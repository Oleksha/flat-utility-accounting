<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        h1, h2 { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: right; }
        th { background: #f2f2f2; }
        td:first-child, th:first-child { text-align: left; }
        .summary td { font-weight: bold; }
    </style>
</head>
<body>

<h1>Отчёт по квартире</h1>
<h2>{{ $apartment->name }}</h2>
<p>{{ $apartment->address }}</p>
<p><strong>Год:</strong> {{ $year }}</p>

<table>
    <thead>
    <tr>
        <th>Месяц</th>
        <th>Начислено</th>
        <th>Оплачено</th>
        <th>Баланс</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>
                {{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->translatedFormat('F Y') }}
            </td>
            <td>{{ number_format($row['charged'], 2, ',', ' ') }}</td>
            <td>{{ number_format($row['paid'], 2, ',', ' ') }}</td>
            <td>{{ number_format($row['balance'], 2, ',', ' ') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="summary">
        <td>Итого</td>
        <td>{{ number_format($totalCharged, 2, ',', ' ') }}</td>
        <td>{{ number_format($totalPaid, 2, ',', ' ') }}</td>
        <td>{{ number_format($totalBalance, 2, ',', ' ') }}</td>
    </tr>
    </tfoot>
</table>

</body>
</html>
