<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\Clanarina;
use App\Models\NastupIgraca;
use App\Models\Selekcija;
use App\Models\Tim;
use App\Models\Utakmica;
use Illuminate\Support\Facades\DB;

class IzvestajController extends Controller
{
    public function __invoke()
    {
        return view('admin.izvestaji', [
            'napredak' => Clan::withSum('nastupi', 'golovi')->withAvg('nastupi', 'ocena_trenera')->withCount('nastupi')->get(),
            'nastupiPoUtakmicama' => NastupIgraca::select('utakmica_id', DB::raw('count(*) as igraca'), DB::raw('sum(golovi) as golovi'), DB::raw('sum(asistencije) as asistencije'))->with('utakmica')->groupBy('utakmica_id')->get(),
            'timovi' => Tim::with(['utakmica', 'selekcija', 'clanovi'])->get(),
            'selekcije' => Selekcija::withCount('clanovi')->with(['utakmice', 'treneri'])->get(),
            'clanarine' => Clanarina::select('status_placanja', DB::raw('count(*) as broj'), DB::raw('sum(iznos) as iznos'))->groupBy('status_placanja')->get(),
            'utakmice' => Utakmica::with('selekcija')->get(),
        ]);
    }
}
