<?php

namespace App\Http\Controllers\Admin;

use App\Models\Selekcija;
use App\Models\Trener;

class TrenerController extends ResourceController
{
    protected string $model = Trener::class;
    protected string $title = 'Treneri';
    protected string $routeBase = 'admin.treneri';
    protected array $with = ['selekcija'];
    protected array $columns = [
        'ime' => 'Ime',
        'prezime' => 'Prezime',
        'telefon' => 'Telefon',
        'licenca' => 'Licenca',
        'selekcija.naziv' => 'Selekcija',
        'datum_zaposlenja' => 'Datum zaposlenja',
    ];
    protected array $rules = [
        'ime' => ['required', 'string', 'max:30'],
        'prezime' => ['required', 'string', 'max:30'],
        'datum_rodjenja' => ['required', 'date'],
        'telefon' => ['required', 'string', 'max:20'],
        'licenca' => ['required', 'string', 'max:50'],
        'datum_zaposlenja' => ['required', 'date'],
        'selekcija_id' => ['nullable', 'exists:selekcije,id'],
    ];

    protected function fields(): array
    {
        return [
            'ime' => ['label' => 'Ime', 'type' => 'text'],
            'prezime' => ['label' => 'Prezime', 'type' => 'text'],
            'datum_rodjenja' => ['label' => 'Datum rođenja', 'type' => 'date'],
            'telefon' => ['label' => 'Telefon', 'type' => 'text'],
            'licenca' => ['label' => 'Licenca', 'type' => 'text'],
            'datum_zaposlenja' => ['label' => 'Datum zaposlenja', 'type' => 'date'],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => ['' => 'Nije dodeljena'] + Selekcija::orderBy('naziv')->pluck('naziv', 'id')->toArray()],
        ];
    }
}
