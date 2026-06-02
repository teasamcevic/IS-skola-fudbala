@extends('layouts.app')

@section('content')
<section>
    <div class="welcome">
        <div class="welcome-grid">
            <div class="welcome-copy">
                <h1>Škola fudbala Novi Pazar</h1>
                <p>Moderan sistem za članove, treninge, utakmice, timove, napredak igrača i članarine.</p>
                <p>
                    <a class="btn" href="{{ route('login') }}">Prijava</a>
                    <a class="btn secondary" href="{{ route('register') }}">Registracija roditelja</a>
                </p>
                <div class="quick-stats">
                    <div><strong>5</strong><span>članova</span></div>
                    <div><strong>3</strong><span>selekcije</span></div>
                    <div><strong>2</strong><span>utakmice</span></div>
                </div>
            </div>
            <div class="hero-photo">
                <img src="{{ asset('images/football-school-hero.png') }}" alt="Trening škole fudbala">
                <div class="match-card">
                    <span>sledeći trening</span>
                    <strong>Pioniri · 18:00</strong>
                </div>
            </div>
        </div>

        <div class="landing-cards">
            <article class="feature-card feature-green">
                <span>01</span>
                <h2>Članovi i selekcije</h2>
                <p>Profil igrača, roditeljski kontakt, status i uzrasna kategorija.</p>
            </article>
            <article class="feature-card feature-blue">
                <span>02</span>
                <h2>Treninzi</h2>
                <p>Raspored, trener, lokacija i evidencija prisustva.</p>
            </article>
            <article class="feature-card feature-orange">
                <span>03</span>
                <h2>Tim za utakmicu</h2>
                <p>Sastav se formira posebno za svaku utakmicu.</p>
            </article>
            <article class="feature-card feature-red">
                <span>04</span>
                <h2>Napredak igrača</h2>
                <p>Minuti, golovi, asistencije, kartoni, ocena i komentar trenera.</p>
            </article>
        </div>

        <div class="landing-strip">
            <div class="strip-image strip-one"></div>
            <div class="strip-copy">
                <h2>Pregled za administratore, trenere i roditelje</h2>
                <p>Svaka uloga vidi ono što joj treba: administrator upravlja sistemom, trener radi sa svojom selekcijom, a roditelj prati podatke svog člana.</p>
            </div>
            <div class="strip-image strip-two"></div>
        </div>
    </div>
</section>
@endsection
