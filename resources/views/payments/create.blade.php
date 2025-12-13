@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Добавить платёж</h2>

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            @include('payments._form')
        </form>
    </div>
@endsection
