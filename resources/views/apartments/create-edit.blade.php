@extends('layouts.app')

@section('content')
    <h1>{{ isset($apartment) ? 'Редактировать квартиру' : 'Добавить квартиру' }}</h1>

    <form action="{{ isset($apartment) ? route('apartments.update', $apartment) : route('apartments.store') }}" method="POST">
        @csrf
        @if(isset($apartment)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Название</label>
            <input type="text" name="name" class="form-control" value="{{ $apartment->name ?? old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Адрес</label>
            <input type="text" name="address" class="form-control" value="{{ $apartment->address ?? old('address') }}">
        </div>

        <button class="btn btn-success" type="submit">Сохранить</button>
        <a class="btn btn-secondary" href="{{ route('apartments.index') }}">Отмена</a>
    </form>
@endsection
