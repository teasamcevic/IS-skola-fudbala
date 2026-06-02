@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Raspored treninga</h1>
        <p class="subhead">Termini selekcije</p>
    </div>
</div>
<div class="table-wrap">
<table>
    <tr><th>Datum</th><th>Vreme</th><th>Lokacija</th><th>Trener</th></tr>
    @forelse($treninzi as $trening)
        <tr><td>{{ $trening->datum }}</td><td>{{ $trening->vreme }}</td><td>{{ $trening->lokacija }}</td><td>{{ $trening->trener->puno_ime }}</td></tr>
    @empty
        <tr><td colspan="4">Nema treninga za prikaz.</td></tr>
    @endforelse
</table>
</div>
@endsection
