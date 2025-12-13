@extends('layouts.app')

@section('content')
    <h1>Услуги</h1>
    <a class="btn btn-primary mb-3" href="{{ route('services.create') }}">Добавить услугу</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->name }}</td>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ route('services.show', $service) }}">Просмотр</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('services.edit', $service) }}">Редактировать</a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" style="display:inline;">
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
