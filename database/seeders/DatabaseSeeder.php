<?php

namespace Database\Seeders;

use App\Models\Clan;
use App\Models\Clanarina;
use App\Models\NastupIgraca;
use App\Models\Selekcija;
use App\Models\Tim;
use App\Models\Trener;
use App\Models\Trening;
use App\Models\User;
use App\Models\Utakmica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $trenerMarko = Trener::create([
            'ime' => 'Marko',
            'prezime' => 'Petrović',
            'datum_rodjenja' => '1984-04-14',
            'telefon' => '060111222',
            'licenca' => 'UEFA B',
            'datum_zaposlenja' => '2021-08-15',
        ]);

        $trenerNikola = Trener::create([
            'ime' => 'Nikola',
            'prezime' => 'Jovanović',
            'datum_rodjenja' => '1990-09-20',
            'telefon' => '060333444',
            'licenca' => 'UEFA C',
            'datum_zaposlenja' => '2022-02-01',
        ]);

        $pioniri = Selekcija::create(['naziv' => 'Pioniri', 'uzrasna_kategorija' => 'U13', 'trener_id' => $trenerMarko->id]);
        $kadeti = Selekcija::create(['naziv' => 'Kadeti', 'uzrasna_kategorija' => 'U15', 'trener_id' => $trenerMarko->id]);
        $juniori = Selekcija::create(['naziv' => 'Juniori', 'uzrasna_kategorija' => 'U17', 'trener_id' => $trenerNikola->id]);

        $clanovi = collect([
            ['Luka', 'Ilić', '2012-03-11', '061111111', 'roditelj@skola.rs', $pioniri->id],
            ['Vuk', 'Savić', '2011-07-21', '061222222', 'vuk.rod@skola.rs', $pioniri->id],
            ['Ognjen', 'Milić', '2010-02-15', '061333333', 'ognjen.rod@skola.rs', $kadeti->id],
            ['Stefan', 'Kostić', '2009-11-09', '061444444', 'stefan.rod@skola.rs', $kadeti->id],
            ['Nemanja', 'Đorđević', '2008-05-30', '061555555', 'nemanja.rod@skola.rs', $juniori->id],
        ])->map(fn ($data) => Clan::create([
            'ime' => $data[0],
            'prezime' => $data[1],
            'datum_rodjenja' => $data[2],
            'telefon_roditelja' => $data[3],
            'email_roditelja' => $data[4],
            'datum_uclanjenja' => '2025-09-01',
            'status_clana' => 'aktivan',
            'selekcija_id' => $data[5],
        ]));

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@skola.rs',
            'password' => Hash::make('password'),
            'role' => 'administrator',
        ]);

        User::create([
            'name' => 'Marko Petrović',
            'email' => 'trener@skola.rs',
            'password' => Hash::make('password'),
            'role' => 'trener',
            'trener_id' => $trenerMarko->id,
        ]);

        User::create([
            'name' => 'Roditelj Luke Ilića',
            'email' => 'roditelj@skola.rs',
            'password' => Hash::make('password'),
            'role' => 'clan_roditelj',
            'clan_id' => $clanovi[0]->id,
        ]);

        $trening1 = Trening::create(['datum' => '2026-06-03', 'vreme' => '18:00', 'lokacija' => 'Teren 1', 'selekcija_id' => $pioniri->id, 'trener_id' => $trenerMarko->id]);
        $trening2 = Trening::create(['datum' => '2026-06-04', 'vreme' => '19:00', 'lokacija' => 'Teren 2', 'selekcija_id' => $kadeti->id, 'trener_id' => $trenerMarko->id]);
        Trening::create(['datum' => '2026-06-05', 'vreme' => '17:30', 'lokacija' => 'Balon sala', 'selekcija_id' => $juniori->id, 'trener_id' => $trenerNikola->id]);

        foreach ($pioniri->clanovi as $clan) {
            $trening1->prisustva()->create(['clan_id' => $clan->id, 'prisutan' => true]);
        }
        foreach ($kadeti->clanovi as $clan) {
            $trening2->prisustva()->create(['clan_id' => $clan->id, 'prisutan' => $clan->ime !== 'Stefan']);
        }

        $utakmica1 = Utakmica::create([
            'datum' => '2026-06-08',
            'vreme' => '11:00',
            'protivnik' => 'FK Novi Pazar',
            'lokacija' => 'Gradski stadion',
            'tip_terena' => 'domaci',
            'selekcija_id' => $pioniri->id,
            'trener_id' => $trenerMarko->id,
            'golovi_domacin' => 3,
            'golovi_gost' => 1,
        ]);

        Utakmica::create([
            'datum' => '2026-06-10',
            'vreme' => '12:30',
            'protivnik' => 'FK Jošanica',
            'lokacija' => 'Sportski centar',
            'tip_terena' => 'gostujuci',
            'selekcija_id' => $kadeti->id,
            'trener_id' => $trenerMarko->id,
            'golovi_domacin' => null,
            'golovi_gost' => null,
        ]);

        $tim = Tim::create([
            'naziv' => 'Pioniri protiv FK Novi Pazar',
            'utakmica_id' => $utakmica1->id,
            'selekcija_id' => $pioniri->id,
            'trener_id' => $trenerMarko->id,
        ]);
        $tim->clanovi()->attach($clanovi[0]->id, ['uloga' => 'starter']);
        $tim->clanovi()->attach($clanovi[1]->id, ['uloga' => 'rezerva']);

        NastupIgraca::create([
            'clan_id' => $clanovi[0]->id,
            'utakmica_id' => $utakmica1->id,
            'odigrani_minuti' => 60,
            'golovi' => 2,
            'asistencije' => 1,
            'zuti_karton' => false,
            'crveni_karton' => false,
            'ocena_trenera' => 9.0,
            'komentar_trenera' => 'Odlična realizacija i dobra disciplina u presingu.',
        ]);

        NastupIgraca::create([
            'clan_id' => $clanovi[1]->id,
            'utakmica_id' => $utakmica1->id,
            'odigrani_minuti' => 25,
            'golovi' => 0,
            'asistencije' => 1,
            'zuti_karton' => false,
            'crveni_karton' => false,
            'ocena_trenera' => 7.5,
            'komentar_trenera' => 'Dobar ulazak sa klupe.',
        ]);

        foreach ($clanovi as $index => $clan) {
            Clanarina::create([
                'clan_id' => $clan->id,
                'iznos' => 3000,
                'datum_od' => '2026-06-01',
                'datum_do' => '2026-06-30',
                'status_placanja' => $index < 3 ? 'placeno' : ($index === 3 ? 'na_cekanju' : 'neplaceno'),
            ]);
        }
    }
}
