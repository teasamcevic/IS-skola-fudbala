<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Selekcija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SelekcijaApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = Selekcija::query()->orderBy('naziv');

        if ($request->user()->role === 'trener') {
            $query->whereKey($request->user()->trener?->selekcija_id ?? 0);
        }

        return response()->json([
            'data' => $query->get(['id', 'naziv', 'uzrasna_kategorija']),
        ]);
    }
}
