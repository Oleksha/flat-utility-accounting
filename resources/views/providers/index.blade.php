@extends('layouts.app')

@section('content')
    <h1>Поставщики</h1>
    <a class="btn btn-primary mb-3" href="{{ route('providers.create') }}">Добавить поставщика</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Сайт</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($providers as $provider)
            <tr>
                <td>{{ $provider->id }}</td>
                <td>{{ $provider->name }}</td>
                <td>{{ $provider->phone }}</td>
                <td>{{ $provider->email }}</td>
                <td>{{ $provider->site_url }}</td>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ route('providers.show', $provider) }}">Просмотр</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('providers.edit', $provider) }}">Редактировать</a>
                    <form action="{{ route('providers.destroy', $provider) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
