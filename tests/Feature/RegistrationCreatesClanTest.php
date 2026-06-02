<?php

namespace Tests\Feature;

use App\Models\Clan;
use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationCreatesClanTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_registration_creates_unassigned_member_profile(): void
    {
        $response = $this->post('/register', [
            'name' => 'Roditelj Test',
            'email' => 'novi.roditelj@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'ime' => 'Lazar',
            'prezime' => 'Testić',
            'datum_rodjenja' => '2013-04-10',
            'telefon_roditelja' => '060999888',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('clanovi', [
            'ime' => 'Lazar',
            'prezime' => 'Testić',
            'email_roditelja' => 'novi.roditelj@example.com',
            'selekcija_id' => null,
            'status_clana' => 'aktivan',
        ]);

        $user = User::where('email', 'novi.roditelj@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('clan_roditelj', $user->role);
        $this->assertNotNull($user->clan_id);
    }

    public function test_trainer_can_assign_unassigned_member_to_own_selection(): void
    {
        $trener = Trener::create([
            'ime' => 'Marko',
            'prezime' => 'Petrović',
            'datum_rodjenja' => '1984-04-14',
            'telefon' => '060111222',
            'licenca' => 'UEFA B',
            'datum_zaposlenja' => '2021-08-15',
        ]);

        $selekcija = Selekcija::create([
            'naziv' => 'Pioniri',
            'uzrasna_kategorija' => 'U13',
            'trener_id' => $trener->id,
        ]);

        $clan = Clan::create([
            'ime' => 'Lazar',
            'prezime' => 'Testić',
            'datum_rodjenja' => '2013-04-10',
            'telefon_roditelja' => '060999888',
            'email_roditelja' => 'novi.roditelj@example.com',
            'datum_uclanjenja' => now()->toDateString(),
            'status_clana' => 'aktivan',
            'selekcija_id' => null,
        ]);

        $user = User::create([
            'name' => 'Marko Petrović',
            'email' => 'trener@example.com',
            'password' => Hash::make('password'),
            'role' => 'trener',
            'trener_id' => $trener->id,
        ]);

        $this->actingAs($user)
            ->post(route('trener.clanovi.dodeli-selekciju', $clan), [
                'selekcija_id' => $selekcija->id,
            ])
            ->assertRedirect();

        $this->assertSame($selekcija->id, $clan->fresh()->selekcija_id);
    }
}
