<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_read_profile_and_logout_through_api(): void
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Tea Samčević',
            'email' => 'tea@example.com',
            'password' => 'password8',
            'password_confirmation' => 'password8',
            'ime' => 'Luka',
            'prezime' => 'Samčević',
            'datum_rodjenja' => '2012-05-10',
            'telefon_roditelja' => '060123456',
        ]);

        $register->assertCreated()
            ->assertJsonPath('user.email', 'tea@example.com')
            ->assertJsonStructure(['message', 'token', 'user']);

        $login = $this->postJson('/api/login', [
            'email' => 'tea@example.com',
            'password' => 'password8',
        ]);

        $token = $login->json('token');
        $login->assertOk()->assertJsonPath('message', 'Uspešno ste se prijavili.');

        $this->withToken($token)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('user.email', 'tea@example.com');

        $this->withToken($token)->postJson('/api/logout')
            ->assertOk();
    }

    public function test_register_and_login_validation_errors_are_json(): void
    {
        User::create([
            'name' => 'Postojeći korisnik',
            'email' => 'postoji@example.com',
            'password' => 'password8',
        ]);

        $this->postJson('/api/register', [
            'name' => '',
            'email' => 'postoji@example.com',
            'password' => '1234567',
            'password_confirmation' => 'druga-lozinka',
            'ime' => 'Luka',
            'prezime' => 'Test',
            'datum_rodjenja' => '2012-05-10',
            'telefon_roditelja' => '060123456',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);

        $this->postJson('/api/login', [
            'email' => 'postoji@example.com',
            'password' => 'pogresna-lozinka',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
