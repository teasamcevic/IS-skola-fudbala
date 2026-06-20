<?php

namespace App\Http\Controllers\Admin;

use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\Utakmica;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UtakmicaController extends ResourceController
{
    protected string $model = Utakmica::class;
    protected string $title = 'Utakmice';
    protected string $routeBase = 'admin.utakmice';
    protected array $with = ['selekcija', 'trener'];
    protected array $columns = ['datum' => 'Datum', 'vreme' => 'Vreme', 'protivnik' => 'Protivnik', 'selekcija.naziv' => 'Selekcija', 'trener.puno_ime' => 'Trener', 'rezultat' => 'Rezultat'];

    protected function fields(): array
    {
        return [
            'datum' => ['label' => 'Datum', 'type' => 'date'],
            'vreme' => ['label' => 'Vreme', 'type' => 'time'],
            'protivnik' => ['label' => 'Protivnik', 'type' => 'text'],
            'lokacija' => ['label' => 'Lokacija', 'type' => 'text'],
            'tip_terena' => ['label' => 'Tip terena', 'type' => 'select', 'options' => ['domaci' => 'Domaći', 'gostujuci' => 'Gostujući', 'neutral' => 'Neutralni']],
            'selekcija_id' => ['label' => 'Selekcija', 'type' => 'select', 'options' => Selekcija::orderBy('naziv')->pluck('naziv', 'id')->toArray()],
            'trener_id' => ['label' => 'Trener', 'type' => 'select', 'options' => $this->trenerOptions()],
            'golovi_domacin' => ['label' => 'Golovi domaćin', 'type' => 'number'],
            'golovi_gost' => ['label' => 'Golovi gost', 'type' => 'number'],
        ];
    }

    protected function validatedData(Request $request, $record = null): array
    {
        $data = $request->validate([
            'datum' => ['required', 'date'],
            'vreme' => ['required'],
            'protivnik' => ['required', 'string', 'max:100'],
            'lokacija' => ['required', 'string', 'max:100'],
            'tip_terena' => ['required', 'in:domaci,gostujuci,neutral'],
            'selekcija_id' => ['required', 'exists:selekcije,id'],
            'trener_id' => ['required', Rule::exists('treneri', 'id')->where('selekcija_id', $request->input('selekcija_id'))],
            'golovi_domacin' => ['nullable', 'integer', 'min:0'],
            'golovi_gost' => ['nullable', 'integer', 'min:0'],
        ]);

        return Arr::only($data, array_keys($this->fields()));
    }

    protected function trenerOptions()
    {
        return Trener::with('selekcija')
            ->whereNotNull('selekcija_id')
            ->orderBy('prezime')
            ->get()
            ->mapWithKeys(fn ($trener) => [$trener->id => $trener->puno_ime.' · '.$trener->selekcija->naziv])
            ->toArray();
    }
}
