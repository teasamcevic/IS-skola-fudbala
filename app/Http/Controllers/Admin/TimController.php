<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\Tim;
use App\Models\Utakmica;
use Illuminate\Http\Request;

class TimController extends Controller
{
    protected string $routeBase = 'admin.timovi';

    public function index()
    {
        return view('admin.timovi.index', [
            'routeBase' => $this->routeBase,
            'timovi' => $this->query()->latest('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.timovi.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $utakmica = Utakmica::findOrFail($data['utakmica_id']);

        $tim = Tim::create([
            'naziv' => $data['naziv'],
            'utakmica_id' => $utakmica->id,
            'selekcija_id' => $utakmica->selekcija_id,
            'trener_id' => $utakmica->trener_id,
        ]);
        $this->syncClanovi($tim, $request->input('igraci', []));

        return redirect()->route($this->routeBase.'.index')->with('success', 'Tim je formiran.');
    }

    public function edit($id)
    {
        return view('admin.timovi.form', $this->formData($this->query()->findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $tim = $this->query()->findOrFail($id);
        $data = $this->validateData($request, $tim->id);
        $utakmica = Utakmica::findOrFail($data['utakmica_id']);

        $tim->update([
            'naziv' => $data['naziv'],
            'utakmica_id' => $utakmica->id,
            'selekcija_id' => $utakmica->selekcija_id,
            'trener_id' => $utakmica->trener_id,
        ]);
        $this->syncClanovi($tim, $request->input('igraci', []));

        return redirect()->route($this->routeBase.'.index')->with('success', 'Sastav tima je ažuriran.');
    }

    public function destroy($id)
    {
        $this->query()->findOrFail($id)->delete();

        return redirect()->route($this->routeBase.'.index')->with('success', 'Tim je obrisan.');
    }

    protected function query()
    {
        return Tim::with(['utakmica', 'selekcija', 'trener', 'clanovi']);
    }

    protected function formData(?Tim $tim = null): array
    {
        return [
            'routeBase' => $this->routeBase,
            'tim' => $tim,
            'utakmice' => Utakmica::with('selekcija')->orderByDesc('datum')->get(),
            'clanovi' => Clan::with('selekcija')->orderBy('prezime')->get(),
            'izabrani' => $tim ? $tim->clanovi->pluck('pivot.uloga', 'id')->toArray() : [],
        ];
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'naziv' => ['required', 'string', 'max:50'],
            'utakmica_id' => ['required', 'exists:utakmice,id', 'unique:timovi,utakmica_id'.($ignoreId ? ','.$ignoreId : '')],
            'igraci' => ['array'],
            'igraci.*.uloga' => ['nullable', 'in:starter,rezerva'],
        ]);
    }

    protected function syncClanovi(Tim $tim, array $igraci): void
    {
        $allowed = Clan::where('selekcija_id', $tim->selekcija_id)->pluck('id')->all();
        $sync = [];

        foreach ($igraci as $clanId => $data) {
            if (in_array((int) $clanId, $allowed, true) && isset($data['izabran'])) {
                $sync[$clanId] = ['uloga' => $data['uloga'] ?? 'rezerva'];
            }
        }

        $tim->clanovi()->sync($sync);
    }
}
