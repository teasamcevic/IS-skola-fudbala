# Railway deployment

Ovaj projekat je sada pripremljen za Railway:

- `Dockerfile` gradi Angular i Laravel u jednom Railway servisu.
- Angular se servira pod `/app`, a Laravel i REST API ostaju na istom HTTPS domenu.
- `railway.toml` govori Railway-u kako da pokrene jedinstveni kontejner.
- `railway/init-app.sh` pokrece migracije pre deploy-a.
- `config/database.php` ume da cita Railway MySQL promenljive.
- sesije su podesene na `file`, da aplikacija ne pukne odmah na `sessions` tabeli.

## 1. Dodaj MySQL na Railway

U istom Railway projektu gde je Laravel app:

1. Klikni `+ New`.
2. Izaberi `Database`.
3. Izaberi `MySQL`.

To mora biti u istom Railway projektu kao aplikacija.

## 2. Podesi Variables za Laravel service

Otvori Laravel/PHP service, ne MySQL service.

Idi na `Variables` i dodaj:

```env
APP_NAME="Skola fudbala Novi Pazar"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${{ RAILWAY_PUBLIC_DOMAIN }}
FRONTEND_URL=https://${{ RAILWAY_PUBLIC_DOMAIN }}/app
CORS_ALLOWED_ORIGINS=https://${{ RAILWAY_PUBLIC_DOMAIN }}
APP_LOCALE=sr
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=sr_RS

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${{ MySQL.MYSQLHOST }}
DB_PORT=${{ MySQL.MYSQLPORT }}
DB_DATABASE=${{ MySQL.MYSQLDATABASE }}
DB_USERNAME=${{ MySQL.MYSQLUSER }}
DB_PASSWORD=${{ MySQL.MYSQLPASSWORD }}

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
CACHE_STORE=file
QUEUE_CONNECTION=sync

RUN_SEEDER=true
```

Ako se database service ne zove `MySQL`, zameni `MySQL` imenom koje Railway prikazuje za taj database service.

## 3. APP_KEY

Lokalno u terminalu pokreni:

```bash
php artisan key:generate --show
```

Dobijenu vrednost dodaj u Railway Variables kao:

```env
APP_KEY=base64:...
```

## 4. Redeploy

Klikni `Redeploy`.

Prvi deploy sa `RUN_SEEDER=true` ce:

- napraviti tabele,
- ubaciti demo podatke,
- napraviti naloge:
  - `admin@skola.rs` / `password`
  - `trener@skola.rs` / `password`
  - `roditelj@skola.rs` / `password`

Kada prvi deploy uspe, promeni:

```env
RUN_SEEDER=false
```

Pa opet redeploy. Tako se demo podaci nece ponavljati.

## Angular i Laravel linkovi

U produkciji nisu hardkodovani `localhost` ili `127.0.0.1` linkovi:

- Angular login: `https://<railway-domain>/app/login`
- Angular registracija: `https://<railway-domain>/app/register`
- Angular novi trening: `https://<railway-domain>/app/treninzi/novi`
- Laravel web stranice: `https://<railway-domain>/admin/...` i `/trener/...`
- REST API: `https://<railway-domain>/api/...`

Pošto sve radi na istom domenu, browser šalje Laravel session cookie i Angular
Sanctum zahteve bez međudomenskih problema.

## Najcesca greska

Ako vidis:

```text
SQLSTATE[HY000] [2002] No such file or directory
```

To znaci da Laravel nije dobio Railway MySQL host i pokusava lokalni MySQL/socket.

Proveri:

- da li postoji MySQL database service,
- da li su DB promenljive dodate u Laravel service,
- da li je `DB_HOST=${{ MySQL.MYSQLHOST }}` i nije `127.0.0.1`,
- da li je `SESSION_DRIVER=file`,
- da li je aplikacija redeploy-ovana posle izmene promenljivih.

XAMPP vazi samo lokalno na tvom racunaru. Railway mora imati svoj MySQL service.
