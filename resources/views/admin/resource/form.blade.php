@extends('layouts.app')

@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $record ? 'Izmena: '.$title : 'Novi zapis: '.$title }}</h1>
        <p class="subhead">Podaci zapisa</p>
    </div>
    <a class="btn secondary" href="{{ route($routeBase.'.index') }}">Nazad</a>
</div>
<div class="card">
    <form method="POST" action="{{ $record ? route($routeBase.'.update', $record) : route($routeBase.'.store') }}">
        @csrf
        @if($record) @method('PUT') @endif
        <div class="form-grid">
            @foreach($fields as $name => $field)
                @php $id = 'field_'.$name; @endphp
                <div>
                    @if(($field['type'] ?? 'text') === 'checkbox')
                        <label class="checkbox-row" for="{{ $id }}">
                            <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" @checked(old($name, data_get($record, $name)))>
                            {{ $field['label'] }}
                        </label>
                    @else
                        <label for="{{ $id }}">{{ $field['label'] }}</label>
                        @if(($field['type'] ?? 'text') === 'select')
                            <select id="{{ $id }}" name="{{ $name }}">
                                @foreach($field['options'] ?? [] as $value => $label)
                                    <option value="{{ $value }}" @selected((string) old($name, data_get($record, $name)) === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @elseif(($field['type'] ?? 'text') === 'textarea')
                            <textarea id="{{ $id }}" name="{{ $name }}">{{ old($name, data_get($record, $name)) }}</textarea>
                        @else
                            <input id="{{ $id }}" type="{{ $field['type'] ?? 'text' }}" name="{{ $name }}" value="{{ old($name, data_get($record, $name)) }}" step="{{ $field['step'] ?? '' }}">
                        @endif
                    @endif
                    @error($name) <div class="error">{{ $message }}</div> @enderror
                </div>
            @endforeach
        </div>
        <p><button type="submit">Sačuvaj</button></p>
    </form>
</div>
@endsection
