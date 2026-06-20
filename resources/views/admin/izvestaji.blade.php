@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>IzveÅ¡taji</h1>
        <p class="subhead">SaÅ¾eci evidencija</p>
    </div>
</div>

<div class="card">
    <h2>Individualni izveÅ¡taj o napretku igraÄa</h2>
    <div class="table-wrap">
    <table>
        <tr><th>IgraÄ</th><th>Nastupi</th><th>Golovi</th><th>ProseÄna ocena</th></tr>
        @foreach($napredak as $clan)
            <tr>
                <td>{{ $clan->puno_ime }}</td>
                <td>{{ $clan->nastupi_count }}</td>
                <td>{{ $clan->nastupi_sum_golovi ?? 0 }}</td>
                <td>{{ number_format($clan->nastupi_avg_ocena_trenera ?? 0, 1) }}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>

<div class="card">
    <h2>Statistika nastupa po utakmicama</h2>
    <div class="table-wrap">
    <table>
        <tr><th>Utakmica</th><th>IgraÄa</th><th>Golovi</th><th>Asistencije</th></tr>
        @foreach($nastupiPoUtakmicama as $red)
            <tr>
                <td>{{ $red->utakmica->datum }} Â· {{ $red->utakmica->protivnik }}</td>
                <td>{{ $red->igraca }}</td>
                <td>{{ $red->golovi }}</td>
                <td>{{ $red->asistencije }}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>

<div class="card">
    <h2>Pregled formiranih timova</h2>
    <div class="table-wrap">
    <table>
        <tr><th>Tim</th><th>Utakmica</th><th>Selekcija</th><th>Broj igraÄa</th></tr>
        @foreach($timovi as $tim)
            <tr>
                <td>{{ $tim->naziv }}</td>
                <td>{{ $tim->utakmica->protivnik }}</td>
                <td>{{ $tim->selekcija->naziv }}</td>
                <td>{{ $tim->clanovi->count() }}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>

<div class="card">
    <h2>UspeÅ¡nost selekcija</h2>
    <div class="table-wrap">
    <table>
        <tr><th>Selekcija</th><th>Treneri</th><th>Članovi</th><th>Utakmice</th><th>Pobede</th><th>Nerešeno</th><th>Porazi</th></tr>
        @foreach($selekcije as $selekcija)
            @php
                $odigrane = $selekcija->utakmice->filter(fn($u) => $u->golovi_domacin !== null && $u->golovi_gost !== null);
                $pobede = $odigrane->filter(fn($u) => $u->golovi_domacin > $u->golovi_gost)->count();
                $nereseno = $odigrane->filter(fn($u) => $u->golovi_domacin === $u->golovi_gost)->count();
                $porazi = $odigrane->filter(fn($u) => $u->golovi_domacin < $u->golovi_gost)->count();
            @endphp
            <tr>
                <td>{{ $selekcija->naziv }}</td>
                <td>{{ $selekcija->treneri_lista }}</td>
                <td>{{ $selekcija->clanovi_count }}</td>
                <td>{{ $odigrane->count() }}</td>
                <td>{{ $pobede }}</td>
                <td>{{ $nereseno }}</td>
                <td>{{ $porazi }}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>

<div class="card">
    <h2>Naplata Älanarina</h2>
    <div class="table-wrap">
    <table>
        <tr><th>Status</th><th>Broj</th><th>Ukupno</th></tr>
        @foreach($clanarine as $red)
            <tr><td>{{ $red->status_placanja }}</td><td>{{ $red->broj }}</td><td>{{ $red->iznos }} RSD</td></tr>
        @endforeach
    </table>
    </div>
</div>
@endsection
