<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\Clanarina;
use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\Trening;
use App\Models\Utakmica;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'metrics' => [
                'Članovi' => Clan::count(),
                'Treneri' => Trener::count(),
                'Selekcije' => Selekcija::count(),
                'Treninzi' => Trening::count(),
                'Utakmice' => Utakmica::count(),
                'Neplaćene članarine' => Clanarina::where('status_placanja', 'neplaceno')->count(),
            ],
            'utakmice' => Utakmica::with('selekcija')->latest('datum')->take(5)->get(),
        ]);
    }
}
