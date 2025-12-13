@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Начисления</h1>
        <a class="btn btn-primary" href="{{ route('charges.create') }}">Добавить начисление</a>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Квартира</th>
            <th>Услуга</th>
            <th>Сумма</th>
            <th>Период</th>
            <th>Комментарий</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($charges as $charge)
            <tr>
                <td>{{ $charge->id }}</td>
                <td>{{ $charge->apartment->name }}</td>
                <td>{{ $charge->service->name }}</td>
                <td>{{ $charge->amount }} ₽</td>
                <td>{{ $charge->period->format('m.Y') }}</td>
                <td>{{ $charge->comment }}</td>
                <td>
                    <a class="btn btn-sm btn-warning" href="{{ route('charges.edit', $charge) }}">Редактировать</a>
                    <form action="{{ route('charges.destroy', $charge) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить начисление?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
