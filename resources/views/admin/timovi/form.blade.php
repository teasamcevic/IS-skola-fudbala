@extends('layouts.app')

@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $tim ? 'Izmena sastava tima' : 'Formiranje tima' }}</h1>
        <p class="subhead">Sastav utakmice</p>
    </div>
    <a class="btn secondary" href="{{ route($routeBase.'.index') }}">Nazad</a>
</div>
<form method="POST" action="{{ $tim ? route($routeBase.'.update', $tim) : route($routeBase.'.store') }}">
    @csrf
    @if($tim) @method('PUT') @endif
    <div class="card">
        <div class="form-grid">
            <div>
                <label for="naziv">Naziv tima</label>
                <input id="naziv" type="text" name="naziv" value="{{ old('naziv', $tim->naziv ?? '') }}" required>
                @error('naziv') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="utakmica_id">Utakmica</label>
                <select id="utakmica_id" name="utakmica_id" required>
                    @foreach($utakmice as $utakmica)
                        <option value="{{ $utakmica->id }}" @selected((string) old('utakmica_id', $tim->utakmica_id ?? '') === (string) $utakmica->id)>
                            {{ $utakmica->datum }} · {{ $utakmica->protivnik }} · {{ $utakmica->selekcija->naziv }}
                        </option>
                    @endforeach
                </select>
                @error('utakmica_id') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
    <div class="card">
        <h2>Izbor igrača</h2>
        <p class="muted">Igrači selekcije utakmice</p>
        <div class="table-wrap">
        <table>
            <tr><th>Izabran</th><th>Igrač</th><th>Selekcija</th><th>Uloga</th></tr>
            @foreach($clanovi as $clan)
                <tr>
                    <td><input id="igrac_{{ $clan->id }}" type="checkbox" name="igraci[{{ $clan->id }}][izabran]" value="1" @checked(isset($izabrani[$clan->id]))></td>
                    <td>{{ $clan->puno_ime }}</td>
                    <td>{{ $clan->selekcija->naziv }}</td>
                    <td>
                        <select aria-label="Uloga za {{ $clan->puno_ime }}" name="igraci[{{ $clan->id }}][uloga]">
                            <option value="starter" @selected(($izabrani[$clan->id] ?? '') === 'starter')>Starter</option>
                            <option value="rezerva" @selected(($izabrani[$clan->id] ?? '') === 'rezerva')>Rezerva</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>
    <button type="submit">Sačuvaj tim</button>
</form>
@endsection
