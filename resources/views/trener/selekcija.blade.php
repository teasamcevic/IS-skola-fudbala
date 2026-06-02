@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Moje selekcije</h1>
        <p class="subhead">Selekcije i članovi</p>
    </div>
</div>
@foreach($selekcije as $selekcija)
    <div class="card">
        <h2>{{ $selekcija->naziv }} · {{ $selekcija->uzrasna_kategorija }}</h2>
        <p class="muted">Broj članova: {{ $selekcija->clanovi->count() }}</p>
        <div class="table-wrap">
        <table>
            <tr><th>Član</th><th>Datum rođenja</th><th>Status</th></tr>
            @foreach($selekcija->clanovi as $clan)
                <tr><td>{{ $clan->puno_ime }}</td><td>{{ $clan->datum_rodjenja }}</td><td>{{ $clan->status_clana }}</td></tr>
            @endforeach
        </table>
        </div>
    </div>
@endforeach
@endsection
