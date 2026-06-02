<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clan;
use App\Models\NastupIgraca;
use App\Models\Utakmica;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NastupController extends ResourceController
{
    protected string $model = NastupIgraca::class;
    protected string $title = 'Napredak igrača';
    protected string $routeBase = 'admin.napredak';
    protected array $with = ['clan', 'utakmica'];
    protected array $columns = ['clan.puno_ime' => 'Igrač', 'utakmica.protivnik' => 'Utakmica', 'odigrani_minuti' => 'Minuti', 'golovi' => 'Golovi', 'asistencije' => 'Asistencije', 'ocena_trenera' => 'Ocena'];
    protected array $rules = [
        'clan_id' => ['required', 'exists:clanovi,id'],
        'utakmica_id' => ['required', 'exists:utakmice,id'],
        'odigrani_minuti' => ['required', 'integer', 'min:0', 'max:130'],
        'golovi' => ['required', 'integer', 'min:0'],
        'asistencije' => ['required', 'integer', 'min:0'],
        'zuti_karton' => ['nullable', 'boolean'],
        'crveni_karton' => ['nullable', 'boolean'],
        'ocena_trenera' => ['nullable', 'numeric', 'min:1', 'max:10'],
        'komentar_trenera' => ['nullable', 'string'],
    ];

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $this->ensureClanBelongsToMatch($data['clan_id'], $data['utakmica_id']);
        NastupIgraca::create($data);

        return redirect()->route($this->routeBase.'.index')->with('success', 'Nastup igrača je evidentiran.');
    }

    public function update(Request $request, $id)
    {
        $record = $this->query()->findOrFail($id);
        $data = $this->validatedData($request, $record);
        $this->ensureClanBelongsToMatch($data['clan_id'], $data['utakmica_id']);
        $record->update($data);

        return redirect()->route($this->routeBase.'.index')->with('success', 'Nastup igrača je ažuriran.');
    }

    protected function fields(): array
    {
        return [
            'clan_id' => ['label' => 'Igrač', 'type' => 'select', 'options' => Clan::orderBy('prezime')->get()->pluck('puno_ime', 'id')->toArray()],
            'utakmica_id' => ['label' => 'Utakmica', 'type' => 'select', 'options' => Utakmica::orderByDesc('datum')->get()->mapWithKeys(fn ($u) => [$u->id => $u->datum.' - '.$u->protivnik])->toArray()],
            'odigrani_minuti' => ['label' => 'Odigrani minuti', 'type' => 'number'],
            'golovi' => ['label' => 'Golovi', 'type' => 'number'],
            'asistencije' => ['label' => 'Asistencije', 'type' => 'number'],
            'zuti_karton' => ['label' => 'Žuti karton', 'type' => 'checkbox'],
            'crveni_karton' => ['label' => 'Crveni karton', 'type' => 'checkbox'],
            'ocena_trenera' => ['label' => 'Ocena trenera', 'type' => 'number', 'step' => '0.1'],
            'komentar_trenera' => ['label' => 'Komentar trenera', 'type' => 'textarea'],
        ];
    }

    protected function ensureClanBelongsToMatch(int $clanId, int $utakmicaId): void
    {
        $utakmica = Utakmica::findOrFail($utakmicaId);
        $clan = Clan::findOrFail($clanId);

        if ($clan->selekcija_id !== $utakmica->selekcija_id) {
            throw ValidationException::withMessages(['clan_id' => 'Igrač mora pripadati selekciji izabrane utakmice.']);
        }
    }
}
