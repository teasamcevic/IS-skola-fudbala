@extends('layouts.app')

@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $record ? 'Izmena treninga' : 'Novi trening' }}</h1>
        <p class="subhead">Termin i prisustvo</p>
    </div>
    <a class="btn secondary" href="{{ route($routeBase.'.index') }}">Nazad</a>
</div>
<form method="POST" action="{{ $record ? route($routeBase.'.update', $record) : route($routeBase.'.store') }}">
    @csrf
    @if($record) @method('PUT') @endif
    <div class="card">
        <h2>Podaci o treningu</h2>
        <div class="form-grid">
            @foreach($fields as $name => $field)
                @php $id = 'field_'.$name; @endphp
                <div>
                    <label for="{{ $id }}">{{ $field['label'] }}</label>
                    @if(($field['type'] ?? 'text') === 'select')
                        <select id="{{ $id }}" name="{{ $name }}">
                            @foreach($field['options'] ?? [] as $value => $label)
                                <option value="{{ $value }}" @selected((string) old($name, data_get($record, $name)) === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="{{ $id }}" type="{{ $field['type'] ?? 'text' }}" name="{{ $name }}" value="{{ old($name, data_get($record, $name)) }}">
                    @endif
                    @error($name) <div class="error">{{ $message }}</div> @enderror
                </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <h2>Evidencija prisustva</h2>
        <p class="muted">Članovi selekcije treninga</p>
        <div class="grid">
            @foreach($clanovi as $clan)
                <label class="checkbox-row" for="prisustvo_{{ $clan->id }}">
                    <input id="prisustvo_{{ $clan->id }}" type="checkbox" name="prisustvo[{{ $clan->id }}]" value="1" @checked(old("prisustvo.$clan->id", $prisustvo[$clan->id] ?? false))>
                    {{ $clan->puno_ime }} <span class="muted">({{ $clan->selekcija->naziv }})</span>
                </label>
            @endforeach
        </div>
    </div>
    <button type="submit">Sačuvaj</button>
</form>
@endsection
