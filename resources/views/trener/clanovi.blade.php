@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Članovi i novi zahtevi</h1>
        <p class="subhead">Nedodeljene članove rasporedi u jednu od svojih selekcija.</p>
    </div>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Ime i prezime</th>
            <th>Selekcija</th>
            <th>Telefon roditelja</th>
            <th>Email roditelja</th>
            <th>Status</th>
            <th>Dodela selekcije</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clanovi as $clan)
            <tr>
                <td>{{ $clan->puno_ime }}</td>
                <td>{{ $clan->selekcija?->naziv ?? 'Nije dodeljena' }}</td>
                <td>{{ $clan->telefon_roditelja }}</td>
                <td>{{ $clan->email_roditelja }}</td>
                <td>{{ $clan->status_clana }}</td>
                <td>
                    <form class="inline-form" method="POST" action="{{ route('trener.clanovi.dodeli-selekciju', $clan) }}">
                        @csrf
                        <select name="selekcija_id" required>
                            <option value="">Izaberi selekciju</option>
                            @foreach($selekcije as $selekcija)
                                <option value="{{ $selekcija->id }}" @selected($clan->selekcija_id === $selekcija->id)>
                                    {{ $selekcija->naziv }} ({{ $selekcija->uzrasna_kategorija }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit">Sačuvaj</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Nema članova za prikaz.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection
