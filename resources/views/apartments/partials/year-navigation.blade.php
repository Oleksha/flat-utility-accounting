@php
    $minYear = $years->min();
    $maxYear = $years->max();
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">

    {{-- Предыдущий год --}}
    <a href="{{ route('apartments.show', [
            'apartment' => $apartment->id,
            'year' => $year - 1
        ]) }}"
       class="btn btn-outline-secondary {{ $year <= $minYear ? 'disabled' : '' }}">
        ⟵ {{ $year - 1 }}
    </a>

    {{-- Выбор года --}}
    <form method="GET" action="{{ route('apartments.show', $apartment->id) }}">
        <select name="year"
                class="form-select"
                onchange="this.form.submit()">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Следующий год --}}
    <a href="{{ route('apartments.show', [
            'apartment' => $apartment->id,
            'year' => $year + 1
        ]) }}"
       class="btn btn-outline-secondary {{ $year >= $maxYear ? 'disabled' : '' }}">
        {{ $year + 1 }} ⟶
    </a>

</div>
