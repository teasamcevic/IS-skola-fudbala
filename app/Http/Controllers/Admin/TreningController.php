<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clan;
use App\Models\PrisustvoTreningu;
use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\Trening;
use Illuminate\Http\Request;

class TreningController extends ResourceController
{
    protected string $model = Trening::class;
    protected string $title = 'Treninzi';
    protected string $routeBase = 'admin.treninzi';
    protected array $with = ['selekcija', 'trener', 'prisustva.clan'];
    protected array $columns = ['datum' => 'Datum', 'vreme' => 'Vreme', 'lokacija' => 'Lokacija', 'selekcija.naziv' => 'Selekcija', 'trener.puno_ime' => 'Trener'];
    protected array $rules = [
        'datum' => ['required', 'date'],
        'vreme' => ['required'],
        'lokacija' => ['required', 'string', 'max:100'],
        'selekcija_id' => ['required', 'exists:selekcije,id'],
        'trener_id' => ['required', 'exists:treneri,id'],
        'prisustvo' => ['array'],
    ];

    public function create()
    {
        return view('admin.treninzi.form', $this->formData());
    }

    public function edit($id)
    {
        return view('admin.treninzi.form', $this->formData($this->query()->findOrFail($id)));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules);
        $trening = Trening::create(collect($data)->except('prisustvo')->toArray());
        $this->syncPrisustvo($trening, $request->input('prisustvo', []));

        return redirect()->route($this->routeBase.'.index')->with('success', 'Trening je sačuvan.');
    }

    public function update(Request $request, $id)
    {
        $trening = $this->query()->findOrFail($id);
        $data = $request->validate($this->rules);
        $trening->update(collect($data)->except('prisustvo')->toArray());
        $this->syncPrisustvo($trening, $request->input('prisustvo', []));

        return redirect()->route($this->routeBase.'.index')->with('success', 'Trening je ažuriran.');
    }

    protected function fields(): array
    {
        return [
            'datum' => ['label' => 'Datum', 'type' => 'date'],
            'vreme' => ['label' => 'Vreme', 'type' => 'time'],
            'lokacija' => ['label' => 'Lokacija', 'type' => 'text'],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => Selekcija::orderBy('naziv')->pluck('naziv', 'id')->toArray()],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => Trener::orderBy('prezime')->get()->pluck('puno_ime', 'id')->toArray()],
        ];
    }

    protected function formData(?Trening $record = null): array
    {
        return [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'record' => $record,
            'fields' => $this->fields(),
            'clanovi' => Clan::with('selekcija')->orderBy('prezime')->get(),
            'prisustvo' => $record ? $record->prisustva->pluck('prisutan', 'clan_id')->toArray() : [],
        ];
    }

    protected function syncPrisustvo(Trening $trening, array $prisustvo): void
    {
        $validClanovi = Clan::where('selekcija_id', $trening->selekcija_id)->pluck('id');
        foreach ($validClanovi as $clanId) {
            PrisustvoTreningu::updateOrCreate(
                ['trening_id' => $trening->id, 'clan_id' => $clanId],
                ['prisutan' => isset($prisustvo[$clanId])]
            );
        }
    }
}
