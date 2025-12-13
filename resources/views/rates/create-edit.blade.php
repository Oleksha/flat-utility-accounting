@extends('layouts.app')

@section('content')
    <h1>{{ isset($rate) ? 'Редактировать тариф' : 'Добавить тариф' }}</h1>

    <form action="{{ isset($rate) ? route('rates.update', $rate) : route('rates.store') }}" method="POST">
        @csrf
        @if(isset($rate)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Квартира</label>
            <select name="apartment_id" class="form-select" required>
                @foreach($apartments as $apartment)
                    <option value="{{ $apartment->id }}" {{ isset($rate) && $rate->apartment_id==$apartment->id ? 'selected' : '' }}>
                        {{ $apartment->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Поставщик</label>
            <select name="provider_id" class="form-select" required>
                @foreach($providers as $provider)
                    <option value="{{ $provider->id }}" {{ isset($rate) && $rate->provider_id==$provider->id ? 'selected' : '' }}>
                        {{ $provider->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Услуга</label>
            <select name="service_id" class="form-select" required>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ isset($rate) && $rate->service_id==$service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Цена</label>
            <input type="number" name="price" class="form-control" value="{{ $rate->price ?? old('price') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Тип</label>
            <select name="type" class="form-select" required>
                <option value="fixed" {{ isset($rate) && $rate->type=='fixed' ? 'selected' : '' }}>Фиксированный</option>
                <option value="metered" {{ isset($rate) && $rate->type=='metered' ? 'selected' : '' }}>По счетчику</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Дата начала</label>
            <input type="date" name="start_date" class="form-control" value="{{ isset($rate) ? $rate->start_date->format('Y-m-d') : old('start_date') }}" required>
        </div>

        <button class="btn btn-success" type="submit">Сохранить</button>
        <a class="btn btn-secondary" href="{{ route('rates.index') }}">Отмена</a>
    </form>
@endsection
