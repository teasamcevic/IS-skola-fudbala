<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trening;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TreningApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $treninzi = $this->visibleQuery($request->user())
            ->orderBy('datum')
            ->orderBy('vreme')
            ->get();

        return response()->json(['data' => $treninzi]);
    }

    public function show(Request $request, int $trening): JsonResponse
    {
        $record = $this->visibleQuery($request->user())->findOrFail($trening);

        return response()->json(['data' => $record]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->ensureCanManage($request->user());
        $data = $this->validatedData($request);
        $this->ensureTrainerScope($request->user(), $data);

        $trening = Trening::create($data)->load(['selekcija', 'trener']);

        return response()->json([
            'message' => 'Trening je uspešno kreiran.',
            'data' => $trening,
        ], 201);
    }

    public function update(Request $request, int $trening): JsonResponse
    {
        $this->ensureCanManage($request->user());
        $record = $this->visibleQuery($request->user())->findOrFail($trening);
        $data = $this->validatedData($request);
        $this->ensureTrainerScope($request->user(), $data);

        $record->update($data);

        return response()->json([
            'message' => 'Trening je uspešno izmenjen.',
            'data' => $record->fresh(['selekcija', 'trener']),
        ]);
    }

    public function destroy(Request $request, int $trening): JsonResponse
    {
        $this->ensureCanManage($request->user());
        $record = $this->visibleQuery($request->user())->findOrFail($trening);
        $record->delete();

        return response()->json(['message' => 'Trening je uspešno obrisan.']);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'datum' => ['required', 'date', 'after_or_equal:today'],
            'vreme' => ['required', 'date_format:H:i'],
            'lokacija' => ['required', 'string', 'max:100'],
            'selekcija_id' => ['required', 'integer', 'exists:selekcije,id'],
            'trener_id' => [
                'required',
                'integer',
                Rule::exists('treneri', 'id')->where(
                    fn ($query) => $query->where('selekcija_id', $request->input('selekcija_id'))
                ),
            ],
        ], [
            'datum.required' => 'Datum treninga je obavezan.',
            'datum.date' => 'Datum treninga nije ispravan.',
            'datum.after_or_equal' => 'Trening nije moguće zakazati u prošlosti.',
            'vreme.required' => 'Vreme treninga je obavezno.',
            'vreme.date_format' => 'Vreme mora biti u formatu HH:mm.',
            'lokacija.required' => 'Lokacija treninga je obavezna.',
            'selekcija_id.required' => 'Selekcija je obavezna.',
            'selekcija_id.exists' => 'Izabrana selekcija ne postoji.',
            'trener_id.required' => 'Trener je obavezan.',
            'trener_id.exists' => 'Trener ne postoji ili nije dodeljen izabranoj selekciji.',
        ]);
    }

    private function visibleQuery(User $user): Builder
    {
        $query = Trening::query()->with(['selekcija', 'trener']);

        if ($user->role === 'trener') {
            return $query->where('trener_id', $user->trener_id);
        }

        if ($user->role === 'clan_roditelj') {
            $selekcijaId = $user->clan?->selekcija_id;

            return $query->where('selekcija_id', $selekcijaId ?? 0);
        }

        return $query;
    }

    private function ensureCanManage(User $user): void
    {
        abort_unless(in_array($user->role, ['administrator', 'trener'], true), 403, 'Nemate dozvolu za upravljanje treninzima.');
    }

    private function ensureTrainerScope(User $user, array $data): void
    {
        if ($user->role !== 'trener') {
            return;
        }

        abort_unless(
            $user->trener_id === (int) $data['trener_id']
                && $user->trener?->selekcija_id === (int) $data['selekcija_id'],
            403,
            'Trener može upravljati samo treninzima svoje selekcije.'
        );
    }
}
