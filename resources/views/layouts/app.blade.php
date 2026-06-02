<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'IS škole fudbala' }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body @class(['landing-page' => request()->routeIs('home')])>
    <header class="topbar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true">⚽</span> Škola fudbala</a>
        <div class="topbar-actions">
            @auth
                <span class="user-chip">{{ auth()->user()->name }} · {{ auth()->user()->role }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="secondary">Logout</button>
                </form>
            @else
                <a class="btn secondary" href="{{ route('login') }}">Login</a>
                <a class="btn" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </header>

    @auth
        <div class="shell">
            <aside class="sidebar">
                @if(auth()->user()->role === 'administrator')
                    <div class="nav-kicker">Administracija</div>
                    <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
                    <a href="{{ route('admin.clanovi.index') }}" @class(['active' => request()->routeIs('admin.clanovi.*')])>Članovi</a>
                    <a href="{{ route('admin.treneri.index') }}" @class(['active' => request()->routeIs('admin.treneri.*')])>Treneri</a>
                    <a href="{{ route('admin.selekcije.index') }}" @class(['active' => request()->routeIs('admin.selekcije.*')])>Selekcije</a>
                    <a href="{{ route('admin.treninzi.index') }}" @class(['active' => request()->routeIs('admin.treninzi.*')])>Treninzi</a>
                    <a href="{{ route('admin.utakmice.index') }}" @class(['active' => request()->routeIs('admin.utakmice.*')])>Utakmice</a>
                    <a href="{{ route('admin.timovi.index') }}" @class(['active' => request()->routeIs('admin.timovi.*')])>Timovi</a>
                    <a href="{{ route('admin.napredak.index') }}" @class(['active' => request()->routeIs('admin.napredak.*')])>Napredak</a>
                    <a href="{{ route('admin.clanarine.index') }}" @class(['active' => request()->routeIs('admin.clanarine.*')])>Članarine</a>
                    <a href="{{ route('admin.izvestaji') }}" @class(['active' => request()->routeIs('admin.izvestaji')])>Izveštaji</a>
                @elseif(auth()->user()->role === 'trener')
                    <div class="nav-kicker">Trener</div>
                    <a href="{{ route('trener.dashboard') }}" @class(['active' => request()->routeIs('trener.dashboard')])>Dashboard</a>
                    <a href="{{ route('trener.selekcija') }}" @class(['active' => request()->routeIs('trener.selekcija')])>Moja selekcija</a>
                    <a href="{{ route('trener.clanovi') }}" @class(['active' => request()->routeIs('trener.clanovi')])>Članovi</a>
                    <a href="{{ route('trener.treninzi.index') }}" @class(['active' => request()->routeIs('trener.treninzi.*')])>Treninzi</a>
                    <a href="{{ route('trener.utakmice.index') }}" @class(['active' => request()->routeIs('trener.utakmice.*')])>Utakmice</a>
                    <a href="{{ route('trener.timovi.index') }}" @class(['active' => request()->routeIs('trener.timovi.*')])>Timovi</a>
                    <a href="{{ route('trener.napredak.index') }}" @class(['active' => request()->routeIs('trener.napredak.*')])>Napredak</a>
                @else
                    <div class="nav-kicker">Roditelj</div>
                    <a href="{{ route('roditelj.dashboard') }}" @class(['active' => request()->routeIs('roditelj.dashboard')])>Dashboard</a>
                    <a href="{{ route('roditelj.profil') }}" @class(['active' => request()->routeIs('roditelj.profil')])>Profil člana</a>
                    <a href="{{ route('roditelj.treninzi') }}" @class(['active' => request()->routeIs('roditelj.treninzi')])>Treninzi</a>
                    <a href="{{ route('roditelj.utakmice') }}" @class(['active' => request()->routeIs('roditelj.utakmice')])>Utakmice</a>
                    <a href="{{ route('roditelj.napredak') }}" @class(['active' => request()->routeIs('roditelj.napredak')])>Napredak</a>
                    <a href="{{ route('roditelj.clanarine') }}" @class(['active' => request()->routeIs('roditelj.clanarine')])>Članarine</a>
                @endif
            </aside>
            <main class="content">
                @if(session('success'))
                    <div class="notice">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</body>
</html>
