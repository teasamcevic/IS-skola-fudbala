<?php

namespace App\Http\Controllers\Admin;

use App\Models\Selekcija;

class SelekcijaController extends ResourceController
{
    protected string $model = Selekcija::class;
    protected string $title = 'Selekcije';
    protected string $routeBase = 'admin.selekcije';
    protected array $with = ['treneri'];
    protected array $columns = [
        'naziv' => 'Naziv',
        'uzrasna_kategorija' => 'Uzrast',
        'treneri_lista' => 'Treneri',
    ];
    protected array $rules = [
        'naziv' => ['required', 'string', 'max:50'],
        'uzrasna_kategorija' => ['required', 'string', 'max:20'],
    ];

    protected function fields(): array
    {
        return [
            'naziv' => ['label' => 'Naziv', 'type' => 'text'],
            'uzrasna_kategorija' => ['label' => 'Uzrasna kategorija', 'type' => 'select', 'options' => ['U9' => 'U9', 'U11' => 'U11', 'U13' => 'U13', 'U15' => 'U15', 'U17' => 'U17', 'U19' => 'U19']],
        ];
    }
}
