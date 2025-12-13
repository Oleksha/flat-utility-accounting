@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Добавить начисление</h2>

        <form method="POST" action="{{ route('charges.store') }}">
            @csrf
            @include('charges._form')
        </form>
    </div>
@endsection
