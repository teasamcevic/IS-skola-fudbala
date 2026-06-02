@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Napredak igrača</h1>
        <p class="subhead">Nastupi i ocene</p>
    </div>
</div>
<div class="table-wrap">
<table>
    <tr><th>Utakmica</th><th>Minuti</th><th>Golovi</th><th>Asistencije</th><th>Kartoni</th><th>Ocena</th><th>Komentar</th></tr>
    @forelse($nastupi as $nastup)
        <tr>
            <td>{{ $nastup->utakmica->datum }} · {{ $nastup->utakmica->protivnik }}</td>
            <td>{{ $nastup->odigrani_minuti }}</td>
            <td>{{ $nastup->golovi }}</td>
            <td>{{ $nastup->asistencije }}</td>
            <td>{{ $nastup->zuti_karton ? 'Žuti ' : '' }}{{ $nastup->crveni_karton ? 'Crveni' : '' }}</td>
            <td>{{ $nastup->ocena_trenera }}</td>
            <td>{{ $nastup->komentar_trenera }}</td>
        </tr>
    @empty
        <tr><td colspan="7">Nema evidentiranih nastupa.</td></tr>
    @endforelse
</table>
</div>
@endsection
