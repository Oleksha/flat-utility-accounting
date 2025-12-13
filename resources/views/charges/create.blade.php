@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Добавить начисление</h2>
        <form method="POST" action="{{ route('charges.copyFromPrevious') }}" class="mb-3">
            @csrf

            <input type="hidden" name="apartment_id"
                   value="{{ old('apartment_id', $apartmentId ?? '') }}">

            <input type="hidden" name="month" value="{{ old('month', now()->month) }}">
            <input type="hidden" name="year"  value="{{ old('year', now()->year) }}">

            <button class="btn btn-outline-secondary"
                    onclick="return confirm('Скопировать начисления с прошлого месяца?')">
                🔁 Скопировать с прошлого месяца
            </button>
        </form>
        <form method="POST" action="{{ route('charges.store') }}">
            @csrf
            @include('charges._form')
        </form>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('change', function () {
                document.querySelector('input[name=month]').value =
                    document.querySelector('select[name=month]').value;

                document.querySelector('input[name=year]').value =
                    document.querySelector('select[name=year]').value;

                document.querySelector('input[name=apartment_id]').value =
                    document.querySelector('select[name=apartment_id]').value;
            });
        </script>
    @endpush
@endsection
