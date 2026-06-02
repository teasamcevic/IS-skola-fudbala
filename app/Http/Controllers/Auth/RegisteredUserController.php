<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'ime' => ['required', 'string', 'max:30'],
            'prezime' => ['required', 'string', 'max:30'],
            'datum_rodjenja' => ['required', 'date', 'before:today'],
            'telefon_roditelja' => ['required', 'string', 'max:20'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $clan = Clan::create([
                'ime' => $data['ime'],
                'prezime' => $data['prezime'],
                'datum_rodjenja' => $data['datum_rodjenja'],
                'telefon_roditelja' => $data['telefon_roditelja'],
                'email_roditelja' => $data['email'],
                'datum_uclanjenja' => now()->toDateString(),
                'status_clana' => 'aktivan',
                'selekcija_id' => null,
            ]);

            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'clan_roditelj',
                'clan_id' => $clan->id,
            ]);
        });

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registracija je uspešno završena. Administrator i trener sada vide novog člana, a selekcija se dodeljuje naknadno.');
    }
}
