@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Trenerski dashboard</h1>
        <p class="subhead">Moje selekcije</p>
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
@endsection
