<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            'administrator' => redirect()->route('admin.dashboard'),
            'trener' => redirect()->route('trener.dashboard'),
            default => redirect()->route('roditelj.dashboard'),
        };
    }
}
