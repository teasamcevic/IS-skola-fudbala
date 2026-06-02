@extends('layouts.app')

@section('content')
<main class="content auth-card">
    <div class="card">
        <h1>Login</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <p>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="password">Lozinka</label>
                <input id="password" type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </p>
            <label class="checkbox-row" for="remember"><input id="remember" type="checkbox" name="remember"> Zapamti me</label>
            <button type="submit">Prijavi se</button>
        </form>
    </div>
</main>
@endsection
