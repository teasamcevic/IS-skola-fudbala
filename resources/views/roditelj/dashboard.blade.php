@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Dashboard roditelja</h1>
        <p class="subhead">Pregled člana</p>
    </div>
</div>
@if($clan)
    <div class="grid">
        <div class="card metric-card"><div class="metric">{{ $clan->puno_ime }}</div><div class="muted">Član</div></div>
        <div class="card metric-card"><div class="metric">{{ $clan->selekcija?->naziv ?? 'Čeka dodelu' }}</div><div class="muted">Selekcija</div></div>
        <div class="card metric-card"><div class="metric">{{ $clan->nastupi->count() }}</div><div class="muted">Nastupi</div></div>
        <div class="card metric-card"><div class="metric">{{ $clan->clanarine->where('status_placanja', 'neplaceno')->count() }}</div><div class="muted">Neplaćene članarine</div></div>
    </div>
@else
    <div class="card">Nalog roditelja još nije povezan sa članom. Administrator treba da poveže clan_id.</div>
@endif
@endsection
