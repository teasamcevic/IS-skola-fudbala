@extends('layouts.app')

@section('content')
<div class="toolbar">
    <div>
        <h1>Timovi za utakmice</h1>
        <p class="subhead">Sastavi po utakmicama</p>
    </div>
    <a class="btn" href="{{ route($routeBase.'.create') }}">Formiraj tim</a>
</div>
<div class="table-wrap">
<table>
    <tr><th>Naziv</th><th>Utakmica</th><th>Selekcija</th><th>Trener</th><th>Igrači</th><th>Akcije</th></tr>
    @forelse($timovi as $tim)
        <tr>
            <td>{{ $tim->naziv }}</td>
            <td>{{ $tim->utakmica->datum }} · {{ $tim->utakmica->protivnik }}</td>
            <td>{{ $tim->selekcija->naziv }}</td>
            <td>{{ $tim->trener->puno_ime }}</td>
            <td>{{ $tim->clanovi->count() }}</td>
            <td>
                <div class="actions">
                    <a class="btn secondary" href="{{ route($routeBase.'.edit', $tim) }}">Izmeni sastav</a>
                    <form method="POST" action="{{ route($routeBase.'.destroy', $tim) }}" onsubmit="return confirm('Obrisati tim?')">
                        @csrf
                        @method('DELETE')
                        <button class="danger" type="submit">Obriši</button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="6">Nema formiranih timova.</td></tr>
    @endforelse
</table>
<div class="pagination">{{ $timovi->links() }}</div>
</div>
@endsection
