<?php

namespace App\Http\Controllers\Roditelj;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use Illuminate\Http\Request;

class RoditeljController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('roditelj.dashboard', ['clan' => $this->clan($request)]);
    }

    public function profil(Request $request)
    {
        return view('roditelj.profil', ['clan' => $this->clan($request)]);
    }

    public function treninzi(Request $request)
    {
        $clan = $this->clan($request);

        return view('roditelj.treninzi', ['clan' => $clan, 'treninzi' => $clan?->selekcija?->treninzi()->with('trener')->orderByDesc('datum')->get() ?? collect()]);
    }

    public function utakmice(Request $request)
    {
        $clan = $this->clan($request);

        return view('roditelj.utakmice', ['clan' => $clan, 'utakmice' => $clan?->selekcija?->utakmice()->with('trener')->orderByDesc('datum')->get() ?? collect()]);
    }

    public function napredak(Request $request)
    {
        $clan = $this->clan($request);

        return view('roditelj.napredak', ['clan' => $clan, 'nastupi' => $clan?->nastupi()->with('utakmica')->orderByDesc('id')->get() ?? collect()]);
    }

    public function clanarine(Request $request)
    {
        $clan = $this->clan($request);

        return view('roditelj.clanarine', ['clan' => $clan, 'clanarine' => $clan?->clanarine()->orderByDesc('datum_od')->get() ?? collect()]);
    }

    private function clan(Request $request): ?Clan
    {
        return Clan::with(['selekcija.trener', 'clanarine', 'nastupi.utakmica'])->find($request->user()->clan_id);
    }
}
