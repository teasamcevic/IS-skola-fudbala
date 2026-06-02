<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trener;

class TrenerController extends ResourceController
{
    protected string $model = Trener::class;
    protected string $title = 'Treneri';
    protected string $routeBase = 'admin.treneri';
    protected array $columns = ['ime' => 'Ime', 'prezime' => 'Prezime', 'telefon' => 'Telefon', 'licenca' => 'Licenca', 'datum_zaposlenja' => 'Datum zaposlenja'];
    protected array $fields = [
        'ime' => ['label' => 'Ime', 'type' => 'text'],
        'prezime' => ['label' => 'Prezime', 'type' => 'text'],
        'datum_rodjenja' => ['label' => 'Datum rođenja', 'type' => 'date'],
        'telefon' => ['label' => 'Telefon', 'type' => 'text'],
        'licenca' => ['label' => 'Licenca', 'type' => 'text'],
        'datum_zaposlenja' => ['label' => 'Datum zaposlenja', 'type' => 'date'],
    ];
    protected array $rules = [
        'ime' => ['required', 'string', 'max:30'],
        'prezime' => ['required', 'string', 'max:30'],
        'datum_rodjenja' => ['required', 'date'],
        'telefon' => ['required', 'string', 'max:20'],
        'licenca' => ['required', 'string', 'max:50'],
        'datum_zaposlenja' => ['required', 'date'],
    ];
}
