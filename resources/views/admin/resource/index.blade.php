@extends('layouts.app')

@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $title }}</h1>
        <p class="subhead">Evidencija</p>
    </div>
    <a class="btn" href="{{ route($routeBase.'.create') }}">Novi zapis</a>
</div>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            @foreach($columns as $label)
                <th>{{ $label }}</th>
            @endforeach
            <th>Akcije</th>
        </tr>
    </thead>
    <tbody>
        @forelse($records as $record)
            <tr>
                @foreach($columns as $field => $label)
                    <td>{{ filled(data_get($record, $field)) ? data_get($record, $field) : 'Nije dodeljena' }}</td>
                @endforeach
                <td>
                    <div class="actions">
                        <a class="btn secondary" href="{{ route($routeBase.'.edit', $record) }}">Izmeni</a>
                        <form method="POST" action="{{ route($routeBase.'.destroy', $record) }}" onsubmit="return confirm('Obrisati zapis?')">
                            @csrf
                            @method('DELETE')
                            <button class="danger" type="submit">Obriši</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($columns) + 1 }}">Nema podataka.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="pagination">{{ $records->links() }}</div>
</div>
@endsection
