<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'ime' => ['required', 'string', 'max:30'],
            'prezime' => ['required', 'string', 'max:30'],
            'datum_rodjenja' => ['required', 'date', 'before:today'],
            'telefon_roditelja' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Ime je obavezno.',
            'email.required' => 'Email je obavezan.',
            'email.email' => 'Email nije u ispravnom formatu.',
            'email.unique' => 'Korisnik sa ovim email-om već postoji.',
            'password.required' => 'Lozinka je obavezna.',
            'password.min' => 'Lozinka mora imati najmanje 8 karaktera.',
            'password.confirmed' => 'Potvrda lozinke se ne poklapa.',
            'ime.required' => 'Ime člana je obavezno.',
            'prezime.required' => 'Prezime člana je obavezno.',
            'datum_rodjenja.required' => 'Datum rođenja člana je obavezan.',
            'datum_rodjenja.before' => 'Datum rođenja mora biti u prošlosti.',
            'telefon_roditelja.required' => 'Telefon roditelja je obavezan.',
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
                'password' => $data['password'],
                'role' => 'clan_roditelj',
                'clan_id' => $clan->id,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('angular-frontend')->plainTextToken;

        return response()->json([
            'message' => 'Uspešno ste se registrovali.',
            'token' => $token,
            'user' => $user,
            'redirect_url' => url('/dashboard'),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email je obavezan.',
            'email.email' => 'Email nije u ispravnom formatu.',
            'password.required' => 'Lozinka je obavezna.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ili lozinka nisu ispravni.'],
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('angular-frontend')->plainTextToken;

        return response()->json([
            'message' => 'Uspešno ste se prijavili.',
            'token' => $token,
            'user' => $user,
            'redirect_url' => url('/dashboard'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        } else {
            $request->user()->tokens()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Uspešno ste se odjavili.']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}
