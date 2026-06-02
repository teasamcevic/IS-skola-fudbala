<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Admin\TreningController as AdminTreningController;
use App\Models\Clan;
use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\Trening;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TreningController extends AdminTreningController
{
    protected string $routeBase = 'trener.treninzi';

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
        $selekcije = Selekcija::where('trener_id', auth()->user()->trener_id)->orderBy('naziv')->pluck('naziv', 'id')->toArray();

        return [
            'datum' => ['label' => 'Datum', 'type' => 'date'],
            'vreme' => ['label' => 'Vreme', 'type' => 'time'],
            'lokacija' => ['label' => 'Lokacija', 'type' => 'text'],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => $selekcije],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => Trener::whereKey(auth()->user()->trener_id)->get()->pluck('puno_ime', 'id')->toArray()],
        ];
    }

    protected function formData(?Trening $record = null): array
    {
        $selekcije = Selekcija::where('trener_id', auth()->user()->trener_id)->pluck('id');

        return [
            'title' => 'Moji treninzi',
            'routeBase' => $this->routeBase,
            'record' => $record,
            'fields' => $this->fields(),
            'clanovi' => Clan::with('selekcija')->whereIn('selekcija_id', $selekcije)->orderBy('prezime')->get(),
            'prisustvo' => $record ? $record->prisustva->pluck('prisutan', 'clan_id')->toArray() : [],
        ];
    }

    private function authorizeSelection(int $selekcijaId): void
    {
        abort_unless(Selekcija::where('trener_id', auth()->user()->trener_id)->whereKey($selekcijaId)->exists(), 403);
    }
}
