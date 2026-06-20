<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrenerApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = Trener::with('selekcija:id,naziv')
            ->orderBy('prezime')
            ->orderBy('ime');

        if ($request->user()->role === 'trener') {
            $query->whereKey($request->user()->trener_id ?? 0);
        }

        $treneri = $query->get()
            ->map(fn (Trener $trener) => [
                'id' => $trener->id,
                'ime' => $trener->ime,
                'prezime' => $trener->prezime,
                'puno_ime' => $trener->puno_ime,
                'selekcija_id' => $trener->selekcija_id,
                'selekcija' => $trener->selekcija,
            ]);

        return response()->json(['data' => $treneri]);
    }
}
