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
        $trenerId = $request->user()->trener_id;
        $selekcije = Selekcija::where('trener_id', $trenerId)->pluck('id');

        return view('trener.dashboard', [
            'metrics' => [
                'Moje selekcije' => $selekcije->count(),
                'Članovi u mojim selekcijama' => Clan::whereIn('selekcija_id', $selekcije)->count(),
                'Nedodeljeni članovi' => Clan::whereNull('selekcija_id')->count(),
                'Treninzi' => Trening::where('trener_id', $trenerId)->count(),
                'Utakmice' => Utakmica::where('trener_id', $trenerId)->count(),
                'Evidentirani nastupi' => NastupIgraca::whereHas('utakmica', fn ($q) => $q->where('trener_id', $trenerId))->count(),
            ],
        ]);
    }

    public function selekcija(Request $request)
    {
        return view('trener.selekcija', [
            'selekcije' => Selekcija::with('clanovi')->where('trener_id', $request->user()->trener_id)->get(),
        ]);
    }

    public function clanovi(Request $request)
    {
        $selekcijeIds = Selekcija::where('trener_id', $request->user()->trener_id)->pluck('id');

        return view('trener.clanovi', [
            'selekcije' => Selekcija::where('trener_id', $request->user()->trener_id)->orderBy('naziv')->get(),
            'clanovi' => Clan::with('selekcija')
                ->where(fn ($query) => $query->whereNull('selekcija_id')->orWhereIn('selekcija_id', $selekcijeIds))
                ->orderByRaw('selekcija_id is not null')
                ->orderBy('prezime')
                ->get(),
        ]);
    }

    public function dodeliSelekciju(Request $request, Clan $clan)
    {
        $selekcijeIds = Selekcija::where('trener_id', $request->user()->trener_id)->pluck('id');

        abort_unless($clan->selekcija_id === null || $selekcijeIds->contains($clan->selekcija_id), 403);

        $data = $request->validate([
            'selekcija_id' => ['required', 'exists:selekcije,id'],
        ]);

        abort_unless($selekcijeIds->contains((int) $data['selekcija_id']), 403);

        $clan->update(['selekcija_id' => $data['selekcija_id']]);

        return back()->with('success', 'Član je dodeljen selekciji.');
    }
}
