# Informacioni sistem škole fudbala

Laravel/PHP projekat za vođenje škole fudbala: članovi, treneri, selekcije, treninzi, utakmice, timovi za utakmice, napredak igrača, članarine i izveštaji.

## Pokretanje

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Ako `composer` nije globalno instaliran, u ovom projektu je moguće koristiti lokalni Composer:

```bash
php composer.phar install
```

U `.env` podesiti MySQL bazu za XAMPP:

```env
DB_DATABASE=is_skola_fudbala
DB_USERNAME=root
DB_PASSWORD=
```

Zatim pokrenuti migracije i seed podatke:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

## Login podaci

- Administrator: `admin@skola.rs` / `password`
- Trener: `trener@skola.rs` / `password`
- Član/Roditelj: `roditelj@skola.rs` / `password`

## Glavne stranice

- `/`
- `/login`
- `/register`
- `/admin/dashboard`
- `/trener/dashboard`
- `/roditelj/dashboard`

## Moduli

- Administrator ima CRUD za članove, trenere, selekcije, treninge, utakmice, timove, napredak i članarine.
- Trener vidi i uređuje samo podatke za svoje selekcije.
- Član/Roditelj ima samo pregled profila, treninga, utakmica, napretka i članarina povezanog člana.
- Tim je vezan za konkretnu utakmicu i selekciju, a pri čuvanju sastava prihvataju se samo članovi iz selekcije utakmice.
- Napredak igrača se vodi kroz nastupe na utakmicama: minuti, golovi, asistencije, kartoni, ocena i komentar trenera.
