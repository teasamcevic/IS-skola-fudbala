<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clan;
use App\Models\Clanarina;

class ClanarinaController extends ResourceController
{
    protected string $model = Clanarina::class;
    protected string $title = 'Članarine';
    protected string $routeBase = 'admin.clanarine';
    protected array $with = ['clan'];
    protected array $columns = ['clan.puno_ime' => 'Član', 'iznos' => 'Iznos', 'datum_od' => 'Od', 'datum_do' => 'Do', 'status_placanja' => 'Status'];
    protected array $rules = [
        'clan_id' => ['required', 'exists:clanovi,id'],
        'iznos' => ['required', 'integer', 'min:1'],
        'datum_od' => ['required', 'date'],
        'datum_do' => ['required', 'date', 'after_or_equal:datum_od'],
        'status_placanja' => ['required', 'in:placeno,neplaceno,na_cekanju'],
    ];

    protected function fields(): array
    {
        return [
            'clan_id' => ['label' => 'Član', 'type' => 'select', 'options' => Clan::orderBy('prezime')->get()->pluck('puno_ime', 'id')->toArray()],
            'iznos' => ['label' => 'Iznos', 'type' => 'number'],
            'datum_od' => ['label' => 'Period od', 'type' => 'date'],
            'datum_do' => ['label' => 'Period do', 'type' => 'date'],
            'status_placanja' => ['label' => 'Status plaćanja', 'type' => 'select', 'options' => ['placeno' => 'Plaćeno', 'neplaceno' => 'Neplaćeno', 'na_cekanju' => 'Na čekanju']],
        ];
    }
}
