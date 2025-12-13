@extends('layouts.app')

@section('content')
    <h1>Поставщик: {{ $provider->name }}</h1>
    <p><strong>Телефон:</strong> {{ $provider->phone }}</p>
    <p><strong>Email:</strong> {{ $provider->email }}</p>
    <p><strong>Сайт:</strong> {{ $provider->site_url }}</p>
    <a class="btn btn-primary" href="{{ route('providers.index') }}">Назад</a>
@endsection
