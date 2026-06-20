<?php

namespace Tests\Feature;

use App\Models\Selekcija;
use App\Models\Trener;
use App\Models\Trening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTreningTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_complete_trening_crud(): void
    {
        [$selekcija, $trener] = $this->selectionAndCoach();
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'password8',
            'role' => 'administrator',
        ]);
        Sanctum::actingAs($admin);

        $created = $this->postJson('/api/treninzi', [
            'datum' => now()->addDay()->toDateString(),
            'vreme' => '18:00',
            'lokacija' => 'Teren 1',
            'selekcija_id' => $selekcija->id,
            'trener_id' => $trener->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->getJson('/api/treninzi')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson("/api/treninzi/{$id}")->assertOk();
        $this->putJson("/api/treninzi/{$id}", [
            'datum' => now()->addDays(2)->toDateString(),
            'vreme' => '19:00',
            'lokacija' => 'Balon sala',
            'selekcija_id' => $selekcija->id,
            'trener_id' => $trener->id,
        ])->assertOk()->assertJsonPath('data.lokacija', 'Balon sala');
        $this->deleteJson("/api/treninzi/{$id}")->assertOk();

        $this->assertDatabaseMissing('treninzi', ['id' => $id]);
    }

    public function test_trening_requires_location_valid_relations_and_non_past_date(): void
    {
        [$selekcija, $trener] = $this->selectionAndCoach();
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'password8',
            'role' => 'administrator',
        ]);
        Sanctum::actingAs($admin);

        $this->postJson('/api/treninzi', [
            'datum' => now()->subDay()->toDateString(),
            'vreme' => '18:00',
            'lokacija' => '',
            'selekcija_id' => $selekcija->id,
            'trener_id' => $trener->id + 999,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['datum', 'lokacija', 'trener_id']);
    }

    public function test_parent_cannot_create_trening(): void
    {
        $parent = User::create([
            'name' => 'Roditelj',
            'email' => 'parent@example.com',
            'password' => 'password8',
            'role' => 'clan_roditelj',
        ]);
        Sanctum::actingAs($parent);

        $this->postJson('/api/treninzi', [])->assertForbidden();
    }

    private function selectionAndCoach(): array
    {
        $selekcija = Selekcija::create([
            'naziv' => 'Pioniri',
            'uzrasna_kategorija' => 'U13',
        ]);
        $trener = Trener::create([
            'ime' => 'Marko',
            'prezime' => 'Petrović',
            'datum_rodjenja' => '1985-01-01',
            'telefon' => '060123456',
            'licenca' => 'UEFA B',
            'datum_zaposlenja' => '2020-01-01',
            'selekcija_id' => $selekcija->id,
        ]);

        return [$selekcija, $trener];
    }
}
