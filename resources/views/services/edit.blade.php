@extends('layouts.app')

@section('content')
    <h1>Редактировать услугу</h1>
    <form action="{{ route('services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
        </div>
        <button class="btn btn-success" type="submit">Сохранить</button>
    </form>
@endsection
