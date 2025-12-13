@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Квартиры</h1>
        <a class="btn btn-primary" href="{{ route('apartments.create') }}">Добавить квартиру</a>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Адрес</th>
            <th>Баланс</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($apartments as $apartment)
            <tr>
                <td>{{ $apartment->id }}</td>
                <td>{{ $apartment->name }}</td>
                <td>{{ $apartment->address }}</td>
                <td>{{ $apartment->charges->sum('amount') - $apartment->payments->sum('amount') }} ₽</td>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ route('apartments.show', $apartment) }}">Просмотр</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('apartments.edit', $apartment) }}">Редактировать</a>
                    <form action="{{ route('apartments.destroy', $apartment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Удалить квартиру?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
