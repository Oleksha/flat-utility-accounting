@extends('layouts.app')

@section('content')
    <h2>Квартира: {{ $apartment->name }}</h2>
    <p class="text-muted"><strong>Адрес:</strong> {{ $apartment->address }}</p>

    <ul class="nav nav-tabs mb-4" id="apartmentTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">
                📊 Обзор
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#charges">
                📄 Начисления
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">
                💳 Платежи
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ОБЗОР --}}
        <div class="tab-pane fade show active" id="overview">
            @include('apartments.tabs.overview')
        </div>

        {{-- НАЧИСЛЕНИЯ --}}
        <div class="tab-pane fade" id="charges">
            @include('apartments.tabs.charges')
        </div>

        {{-- ПЛАТЕЖИ --}}
        <div class="tab-pane fade" id="payments">
            @include('apartments.tabs.payments')
        </div>

    </div>
@endsection
