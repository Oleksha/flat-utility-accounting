@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Редактировать начисление</h2>

        <form method="POST" action="{{ route('charges.update', $charge) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('charges._form')
        </form>
    </div>
@endsection
