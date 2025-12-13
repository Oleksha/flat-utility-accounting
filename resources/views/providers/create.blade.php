@extends('layouts.app')

@section('content')
    <h1>Добавить поставщика</h1>
    <form action="{{ route('providers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Телефон</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Сайт</label>
            <input type="text" name="site_url" class="form-control">
        </div>
        <button class="btn btn-success" type="submit">Сохранить</button>
    </form>
@endsection
