<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Admin\TimController as AdminTimController;
use App\Models\Clan;
use App\Models\Tim;
use App\Models\Utakmica;
use Illuminate\Http\Request;

class TimController extends AdminTimController
{
    protected string $routeBase = 'trener.timovi';

    protected function query()
    {
        return Tim::with(['utakmica', 'selekcija', 'trener', 'clanovi'])->where('trener_id', auth()->user()->trener_id);
    }

    protected function formData(?Tim $tim = null): array
    {
        $utakmice = Utakmica::with('selekcija')->where('trener_id', auth()->user()->trener_id)->orderByDesc('datum')->get();
        $selekcije = $utakmice->pluck('selekcija_id')->unique();

        return [
            'routeBase' => $this->routeBase,
            'tim' => $tim,
            'utakmice' => $utakmice,
            'clanovi' => Clan::with('selekcija')->whereIn('selekcija_id', $selekcije)->orderBy('prezime')->get(),
            'izabrani' => $tim ? $tim->clanovi->pluck('pivot.uloga', 'id')->toArray() : [],
        ];
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

    private function authorizeMatch(int $utakmicaId): void
    {
        abort_unless(Utakmica::where('trener_id', auth()->user()->trener_id)->whereKey($utakmicaId)->exists(), 403);
    }
}
