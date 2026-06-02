<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Admin\NastupController as AdminNastupController;
use App\Models\Clan;
use App\Models\Selekcija;
use App\Models\Utakmica;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NastupController extends AdminNastupController
{
    protected string $title = 'Napredak mojih igrača';
    protected string $routeBase = 'trener.napredak';

    protected function query(): Builder
    {
        return parent::query()->whereHas('utakmica', fn ($q) => $q->where('trener_id', auth()->user()->trener_id));
    }

    public function store(Request $request)
    {
        $this->authorizeMatch((int) $request->input('utakmica_id'));

        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeMatch((int) $request->input('utakmica_id'));

        return parent::update($request, $id);
    }

    protected function fields(): array
    {
        $selekcije = Selekcija::where('trener_id', auth()->user()->trener_id)->pluck('id');

        return [
            'clan_id' => ['label' => 'Igrač', 'type' => 'select', 'options' => Clan::whereIn('selekcija_id', $selekcije)->orderBy('prezime')->get()->pluck('puno_ime', 'id')->toArray()],
            'utakmica_id' => ['label' => 'Utakmica', 'type' => 'select', 'options' => Utakmica::where('trener_id', auth()->user()->trener_id)->orderByDesc('datum')->get()->mapWithKeys(fn ($u) => [$u->id => $u->datum.' - '.$u->protivnik])->toArray()],
            'odigrani_minuti' => ['label' => 'Odigrani minuti', 'type' => 'number'],
            'golovi' => ['label' => 'Golovi', 'type' => 'number'],
            'asistencije' => ['label' => 'Asistencije', 'type' => 'number'],
            'zuti_karton' => ['label' => 'Žuti karton', 'type' => 'checkbox'],
            'crveni_karton' => ['label' => 'Crveni karton', 'type' => 'checkbox'],
            'ocena_trenera' => ['label' => 'Ocena trenera', 'type' => 'number', 'step' => '0.1'],
            'komentar_trenera' => ['label' => 'Komentar trenera', 'type' => 'textarea'],
        ];
    }

    private function authorizeMatch(int $utakmicaId): void
    {
        abort_unless(Utakmica::where('trener_id', auth()->user()->trener_id)->whereKey($utakmicaId)->exists(), 403);
    }
}
