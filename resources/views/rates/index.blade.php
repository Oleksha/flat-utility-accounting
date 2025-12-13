@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Тарифы</h1>
        <a class="btn btn-primary" href="{{ route('rates.create') }}">Добавить тариф</a>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Квартира</th>
            <th>Поставщик</th>
            <th>Услуга</th>
            <th>Цена</th>
            <th>Тип</th>
            <th>Дата начала</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rates as $rate)
            <tr>
                <td>{{ $rate->id }}</td>
                <td>{{ $rate->apartment->name }}</td>
                <td>{{ $rate->provider->name }}</td>
                <td>{{ $rate->service->name }}</td>
                <td>{{ $rate->price }} ₽</td>
                <td>{{ ucfirst($rate->type) }}</td>
                <td>{{ $rate->start_date->format('d.m.Y') }}</td>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ route('rates.show', $rate) }}">Просмотр</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('rates.edit', $rate) }}">Редактировать</a>
                    <form action="{{ route('rates.destroy', $rate) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить тариф?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
