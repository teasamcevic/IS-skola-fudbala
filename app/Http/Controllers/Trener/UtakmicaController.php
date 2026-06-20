<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Admin\UtakmicaController as AdminUtakmicaController;
use App\Models\Trener;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UtakmicaController extends AdminUtakmicaController
{
    protected string $title = 'Moje utakmice';
    protected string $routeBase = 'trener.utakmice';

    protected function query(): Builder
    {
        return parent::query()->where('trener_id', auth()->user()->trener_id);
    }

    public function store(Request $request)
    {
        $request->merge(['trener_id' => auth()->user()->trener_id]);
        $this->authorizeSelection((int) $request->input('selekcija_id'));

        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['trener_id' => auth()->user()->trener_id]);
        $this->authorizeSelection((int) $request->input('selekcija_id'));

        return parent::update($request, $id);
    }

    protected function fields(): array
    {
        $selekcija = auth()->user()->trener?->selekcija;

        return [
            'datum' => ['label' => 'Datum', 'type' => 'date'],
            'vreme' => ['label' => 'Vreme', 'type' => 'time'],
            'protivnik' => ['label' => 'Protivnik', 'type' => 'text'],
            'lokacija' => ['label' => 'Lokacija', 'type' => 'text'],
            'tip_terena' => ['label' => 'Tip terena', 'type' => 'select', 'options' => ['domaci' => 'Domaći', 'gostujuci' => 'Gostujući', 'neutral' => 'Neutralni']],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => $selekcija ? [$selekcija->id => $selekcija->naziv] : []],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => Trener::whereKey(auth()->user()->trener_id)->get()->pluck('puno_ime', 'id')->toArray()],
            'golovi_domacin' => ['label' => 'Golovi domaćin', 'type' => 'number'],
            'golovi_gost' => ['label' => 'Golovi gost', 'type' => 'number'],
        ];
    }

    private function authorizeSelection(int $selekcijaId): void
    {
        abort_unless(auth()->user()->trener?->selekcija_id === $selekcijaId, 403);
    }
}
