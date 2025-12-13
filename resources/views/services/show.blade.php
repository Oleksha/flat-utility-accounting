@extends('layouts.app')

@section('content')
    <h1>Услуга: {{ $service->name }}</h1>
    <a class="btn btn-primary" href="{{ route('services.index') }}">Назад</a>
@endsection
