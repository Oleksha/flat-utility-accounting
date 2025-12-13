@extends('layouts.app')

@section('content')
    <h1>Редактировать поставщика</h1>
    <form action="{{ route('providers.update', $provider) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="name" class="form-control" value="{{ $provider->name }}" required>
        </div>
        <div class="mb-3">
            <label>Телефон</label>
            <input type="text" name="phone" class="form-control" value="{{ $provider->phone }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $provider->email }}">
        </div>
        <div class="mb-3">
            <label>Сайт</label>
            <input type="text" name="site_url" class="form-control" value="{{ $provider->site_url }}">
        </div>
        <button class="btn btn-success" type="submit">Сохранить</button>
    </form>
@endsection
