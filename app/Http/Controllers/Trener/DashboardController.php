<?php

namespace App\Http\Controllers\Trener;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\NastupIgraca;
use App\Models\Selekcija;
use App\Models\Trening;
use App\Models\Utakmica;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $trener = $request->user()->trener;
        $selekcijaId = $trener?->selekcija_id;

        return view('trener.dashboard', [
            'metrics' => [
                'Moja selekcija' => $trener?->selekcija?->naziv ?? 'Nije dodeljena',
                'Članovi u mojoj selekciji' => $selekcijaId ? Clan::where('selekcija_id', $selekcijaId)->count() : 0,
                'Nedodeljeni članovi' => Clan::whereNull('selekcija_id')->count(),
                'Treninzi' => Trening::where('trener_id', $trener?->id)->count(),
                'Utakmice' => Utakmica::where('trener_id', $trener?->id)->count(),
                'Evidentirani nastupi' => NastupIgraca::whereHas('utakmica', fn ($q) => $q->where('trener_id', $trener?->id))->count(),
            ],
        ]);
    }

    public function selekcija(Request $request)
    {
        $selekcijaId = $request->user()->trener?->selekcija_id;

        return view('trener.selekcija', [
            'selekcije' => Selekcija::with(['clanovi', 'treneri'])->whereKey($selekcijaId)->get(),
        ]);
    }

    public function clanovi(Request $request)
    {
        $selekcija = $request->user()->trener?->selekcija;

        return view('trener.clanovi', [
            'selekcije' => $selekcija ? collect([$selekcija]) : collect(),
            'clanovi' => Clan::with('selekcija')
                ->where(fn ($query) => $query->whereNull('selekcija_id')->when($selekcija, fn ($q) => $q->orWhere('selekcija_id', $selekcija->id)))
                ->orderByRaw('selekcija_id is not null')
                ->orderBy('prezime')
                ->get(),
        ]);
    }

    public function dodeliSelekciju(Request $request, Clan $clan)
    {
        $selekcijaId = $request->user()->trener?->selekcija_id;

        abort_unless($selekcijaId, 403);
        abort_unless($clan->selekcija_id === null || $clan->selekcija_id === $selekcijaId, 403);

        $request->validate([
            'selekcija_id' => ['required', 'exists:selekcije,id'],
        ]);

        abort_unless((int) $request->input('selekcija_id') === $selekcijaId, 403);

        $clan->update(['selekcija_id' => $selekcijaId]);

        return back()->with('success', 'Član je dodeljen selekciji.');
    }
}
