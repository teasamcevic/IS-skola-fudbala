@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Profil člana</h1>
        <p class="subhead">Osnovni podaci</p>
    </div>
</div>
@if($clan)
    <div class="card">
        <div class="table-wrap">
        <table>
            <tr><th>Ime i prezime</th><td>{{ $clan->puno_ime }}</td></tr>
            <tr><th>Datum rođenja</th><td>{{ $clan->datum_rodjenja }}</td></tr>
            <tr><th>Telefon roditelja</th><td>{{ $clan->telefon_roditelja }}</td></tr>
            <tr><th>Email roditelja</th><td>{{ $clan->email_roditelja }}</td></tr>
            <tr><th>Datum učlanjenja</th><td>{{ $clan->datum_uclanjenja }}</td></tr>
            <tr><th>Status</th><td>{{ $clan->status_clana }}</td></tr>
            <tr><th>Selekcija</th><td>{{ $clan->selekcija ? $clan->selekcija->naziv.' · '.$clan->selekcija->uzrasna_kategorija : 'Čeka dodelu selekcije' }}</td></tr>
            <tr><th>Trener</th><td>{{ $clan->selekcija?->trener?->puno_ime ?? 'Nije dodeljen' }}</td></tr>
        </table>
        </div>
    </div>
@else
    <div class="card">Nalog nije povezan sa članom.</div>
@endif
@endsection
