@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Administratorski dashboard</h1>
        <p class="subhead">Stanje sistema</p>
    </div>
</div>
<div class="grid">
    @foreach($metrics as $label => $value)
        <div class="card metric-card">
            <div class="metric">{{ $value }}</div>
            <div class="muted">{{ $label }}</div>
        </div>
    @endforeach
</div>
<div class="card">
    <h2>Poslednje utakmice</h2>
    <div class="table-wrap">
    <table>
        <tr><th>Datum</th><th>Protivnik</th><th>Selekcija</th><th>Rezultat</th></tr>
        @foreach($utakmice as $utakmica)
            <tr>
                <td>{{ $utakmica->datum }}</td>
                <td>{{ $utakmica->protivnik }}</td>
                <td>{{ $utakmica->selekcija->naziv }}</td>
                <td>{{ $utakmica->rezultat }}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>
@endsection
