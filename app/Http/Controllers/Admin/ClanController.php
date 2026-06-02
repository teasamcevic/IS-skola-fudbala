<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clan;
use App\Models\Selekcija;

class ClanController extends ResourceController
{
    protected string $model = Clan::class;
    protected string $title = 'Članovi';
    protected string $routeBase = 'admin.clanovi';
    protected array $with = ['selekcija'];
    protected array $columns = ['ime' => 'Ime', 'prezime' => 'Prezime', 'selekcija.naziv' => 'Selekcija', 'telefon_roditelja' => 'Telefon roditelja', 'status_clana' => 'Status'];
    protected array $rules = [
        'ime' => ['required', 'string', 'max:30'],
        'prezime' => ['required', 'string', 'max:30'],
        'datum_rodjenja' => ['required', 'date'],
        'telefon_roditelja' => ['required', 'string', 'max:20'],
        'email_roditelja' => ['nullable', 'email', 'max:100'],
        'datum_uclanjenja' => ['required', 'date'],
        'status_clana' => ['required', 'in:aktivan,neaktivan,suspendovan'],
        'selekcija_id' => ['nullable', 'exists:selekcije,id'],
    ];

    protected function fields(): array
    {
        return [
            'ime' => ['label' => 'Ime', 'type' => 'text'],
            'prezime' => ['label' => 'Prezime', 'type' => 'text'],
            'datum_rodjenja' => ['label' => 'Datum rođenja', 'type' => 'date'],
            'telefon_roditelja' => ['label' => 'Telefon roditelja', 'type' => 'text'],
            'email_roditelja' => ['label' => 'Email roditelja', 'type' => 'email'],
            'datum_uclanjenja' => ['label' => 'Datum učlanjenja', 'type' => 'date'],
            'status_clana' => ['label' => 'Status člana', 'type' => 'select', 'options' => ['aktivan' => 'Aktivan', 'neaktivan' => 'Neaktivan', 'suspendovan' => 'Suspendovan']],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => ['' => 'Nije dodeljena'] + Selekcija::orderBy('naziv')->pluck('naziv', 'id')->toArray()],
        ];
    }
}
