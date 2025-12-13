@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Редактировать платёж</h2>

        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PUT')
            @include('payments._form')
        </form>
    </div>
@endsection
