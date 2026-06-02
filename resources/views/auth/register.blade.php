@extends('layouts.app')

@section('content')
<main class="content auth-card">
    <div class="card">
        <h1>Registracija roditelja</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2>Podaci za nalog</h2>
            <p>
                <label for="name">Ime i prezime roditelja</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="password">Lozinka</label>
                <input id="password" type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="password_confirmation">Potvrda lozinke</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </p>
            <h2>Podaci o članu</h2>
            <p>
                <label for="ime">Ime člana</label>
                <input id="ime" type="text" name="ime" value="{{ old('ime') }}" required>
                @error('ime') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="prezime">Prezime člana</label>
                <input id="prezime" type="text" name="prezime" value="{{ old('prezime') }}" required>
                @error('prezime') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="datum_rodjenja">Datum rođenja člana</label>
                <input id="datum_rodjenja" type="date" name="datum_rodjenja" value="{{ old('datum_rodjenja') }}" required>
                @error('datum_rodjenja') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="telefon_roditelja">Telefon roditelja</label>
                <input id="telefon_roditelja" type="text" name="telefon_roditelja" value="{{ old('telefon_roditelja') }}" required>
                @error('telefon_roditelja') <span class="error">{{ $message }}</span> @enderror
            </p>
            <button type="submit">Registruj člana</button>
        </form>
    </div>
</main>
@endsection
