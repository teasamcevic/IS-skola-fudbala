<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Admin\UtakmicaController as AdminUtakmicaController;
use App\Models\Selekcija;
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
        return [
            'datum' => ['label' => 'Datum', 'type' => 'date'],
            'vreme' => ['label' => 'Vreme', 'type' => 'time'],
            'protivnik' => ['label' => 'Protivnik', 'type' => 'text'],
            'lokacija' => ['label' => 'Lokacija', 'type' => 'text'],
            'tip_terena' => ['label' => 'Tip terena', 'type' => 'select', 'options' => ['domaci' => 'Domaći', 'gostujuci' => 'Gostujući', 'neutral' => 'Neutralni']],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => Selekcija::where('trener_id', auth()->user()->trener_id)->orderBy('naziv')->pluck('naziv', 'id')->toArray()],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => Trener::whereKey(auth()->user()->trener_id)->get()->pluck('puno_ime', 'id')->toArray()],
            'golovi_domacin' => ['label' => 'Golovi domaćin', 'type' => 'number'],
            'golovi_gost' => ['label' => 'Golovi gost', 'type' => 'number'],
        ];
    }

    private function authorizeSelection(int $selekcijaId): void
    {
        abort_unless(Selekcija::where('trener_id', auth()->user()->trener_id)->whereKey($selekcijaId)->exists(), 403);
    }
}
