@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Utakmice selekcije</h1>
        <p class="subhead">Raspored i rezultati</p>
    </div>
</div>
<div class="table-wrap">
<table>
    <tr><th>Datum</th><th>Vreme</th><th>Protivnik</th><th>Lokacija</th><th>Rezultat</th></tr>
    @forelse($utakmice as $utakmica)
        <tr>
            <td>{{ $utakmica->datum }}</td>
            <td>{{ $utakmica->vreme }}</td>
            <td>{{ $utakmica->protivnik }}</td>
            <td>{{ $utakmica->lokacija }}</td>
            <td>{{ $utakmica->rezultat }}</td>
        </tr>
    @empty
        <tr><td colspan="5">Nema utakmica za prikaz.</td></tr>
    @endforelse
</table>
</div>
@endsection
