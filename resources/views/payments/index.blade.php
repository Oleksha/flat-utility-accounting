@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Платежи</h1>
        <a class="btn btn-primary" href="{{ route('payments.create') }}">Добавить платеж</a>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Квартира</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Комментарий</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->apartment->name }}</td>
                <td>{{ $payment->amount }} ₽</td>
                <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                <td>{{ $payment->comment }}</td>
                <td>
                    <a class="btn btn-sm btn-warning" href="{{ route('payments.edit', $payment) }}">Редактировать</a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить платеж?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
