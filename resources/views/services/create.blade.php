@extends('layouts.app')

@section('content')
    <h1>Добавить услугу</h1>
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button class="btn btn-success" type="submit">Сохранить</button>
    </form>
@endsection
