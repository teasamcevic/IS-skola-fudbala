<?php

namespace App\Http\Controllers\Admin;

use App\Models\Selekcija;
use App\Models\Trener;

class SelekcijaController extends ResourceController
{
    protected string $model = Selekcija::class;
    protected string $title = 'Selekcije';
    protected string $routeBase = 'admin.selekcije';
    protected array $with = ['trener'];
    protected array $columns = ['naziv' => 'Naziv', 'uzrasna_kategorija' => 'Uzrast', 'trener.puno_ime' => 'Trener'];
    protected array $rules = [
        'naziv' => ['required', 'string', 'max:50'],
        'uzrasna_kategorija' => ['required', 'string', 'max:20'],
        'trener_id' => ['required', 'exists:treneri,id'],
    ];

    protected function fields(): array
    {
        return [
            'naziv' => ['label' => 'Naziv', 'type' => 'text'],
            'uzrasna_kategorija' => ['label' => 'Uzrasna kategorija', 'type' => 'select', 'options' => ['U9' => 'U9', 'U11' => 'U11', 'U13' => 'U13', 'U15' => 'U15', 'U17' => 'U17', 'U19' => 'U19']],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => Trener::orderBy('prezime')->get()->pluck('puno_ime', 'id')->toArray()],
        ];
    }
}
