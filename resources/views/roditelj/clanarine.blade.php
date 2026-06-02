@extends('layouts.app')

@section('content')
<div class="page-head">
    <div>
        <h1>Članarine</h1>
        <p class="subhead">Status plaćanja</p>
    </div>
</div>
<div class="table-wrap">
<table>
    <tr><th>Period</th><th>Iznos</th><th>Status</th></tr>
    @forelse($clanarine as $clanarina)
        <tr>
            <td>{{ $clanarina->datum_od }} - {{ $clanarina->datum_do }}</td>
            <td>{{ $clanarina->iznos }} RSD</td>
            <td>{{ $clanarina->status_placanja }}</td>
        </tr>
    @empty
        <tr><td colspan="3">Nema članarina za prikaz.</td></tr>
    @endforelse
</table>
</div>
@endsection
