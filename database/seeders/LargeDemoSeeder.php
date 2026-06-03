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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LargeDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $password = Hash::make('password');

            $treneri = $this->createTreneri($password);
            $selekcije = $this->createSelekcije($treneri);
            $treneriPoSelekciji = $this->assignTreneriToSelekcije($treneri, $selekcije);
            $clanovi = $this->createClanovi($selekcije, $password);

            $this->createAdminUser($password);
            $this->createTreninzi($selekcije, $treneriPoSelekciji, $clanovi);
            $utakmice = $this->createUtakmice($selekcije, $treneriPoSelekciji);
            $this->createTimoviINastupi($utakmice, $clanovi);
            $this->createClanarine($clanovi);
        });
    }

    private function createSelekcije(array $treneri): array
    {
        $data = [
            ['Demo Petlici A', 'U9'],
            ['Demo Petlici B', 'U9'],
            ['Demo Mladji pioniri A', 'U11'],
            ['Demo Mladji pioniri B', 'U11'],
            ['Demo Pioniri A', 'U13'],
            ['Demo Pioniri B', 'U13'],
            ['Demo Kadeti A', 'U15'],
            ['Demo Kadeti B', 'U15'],
            ['Demo Juniori A', 'U17'],
            ['Demo Juniori B', 'U17'],
            ['Demo Omladinci A', 'U19'],
            ['Demo Omladinci B', 'U19'],
        ];

        $selekcije = [];

        foreach ($data as $index => [$naziv, $uzrast]) {
            $attributes = ['uzrasna_kategorija' => $uzrast];

            if (Schema::hasColumn('selekcije', 'trener_id')) {
                $attributes['trener_id'] = $treneri[$index % count($treneri)]->id;
            }

            $selekcije[$naziv] = Selekcija::updateOrCreate(
                ['naziv' => $naziv],
                $attributes
            );
        }

        return $selekcije;
    }

    private function createTreneri(string $password): array
    {
        $names = [
            ['Milan', 'Radovic', 'UEFA A'],
            ['Marko', 'Petrovic', 'UEFA B'],
            ['Nikola', 'Jovanovic', 'UEFA C'],
            ['Aleksandar', 'Mehmedovic', 'UEFA B'],
            ['Emir', 'Hadzic', 'UEFA A'],
            ['Ivan', 'Kostic', 'UEFA B'],
            ['Adem', 'Ljajic', 'UEFA C'],
            ['Dusan', 'Stankovic', 'UEFA B'],
            ['Tarik', 'Zukorlic', 'UEFA C'],
            ['Stefan', 'Milosevic', 'UEFA B'],
            ['Nermin', 'Kucevic', 'UEFA A'],
            ['Filip', 'Djordjevic', 'UEFA C'],
        ];

        $treneri = [];

        foreach ($names as $index => [$ime, $prezime, $licenca]) {
            $attributes = [
                'ime' => $ime,
                'prezime' => $prezime,
                'datum_rodjenja' => Carbon::create(1979 + ($index % 12), 2 + ($index % 10), 4 + ($index % 20))->toDateString(),
                'licenca' => $licenca,
                'datum_zaposlenja' => Carbon::create(2018 + ($index % 6), 1 + ($index % 8), 1 + ($index % 20))->toDateString(),
            ];

            $trener = Trener::updateOrCreate(
                ['telefon' => '063700'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                $attributes
            );

            User::updateOrCreate(
                ['email' => 'demo.trener'.($index + 1).'@skola.rs'],
                [
                    'name' => $ime.' '.$prezime,
                    'password' => $password,
                    'role' => 'trener',
                    'trener_id' => $trener->id,
                ]
            );

            $treneri[] = $trener;
        }

        return $treneri;
    }

    private function assignTreneriToSelekcije(array $treneri, array $selekcije): array
    {
        $selectionValues = array_values($selekcije);
        $treneriPoSelekciji = [];

        foreach ($treneri as $index => $trener) {
            $selekcija = $selectionValues[$index % count($selectionValues)];

            if (Schema::hasColumn('treneri', 'selekcija_id')) {
                $trener->update(['selekcija_id' => $selekcija->id]);
            }

            $treneriPoSelekciji[$selekcija->naziv][] = $trener->fresh() ?? $trener;
        }

        return $treneriPoSelekciji;
    }

    private function createClanovi(array $selekcije, string $password): array
    {
        $imena = [
            'Luka', 'Vuk', 'Ognjen', 'Stefan', 'Nemanja', 'Filip', 'Uros', 'Viktor',
            'Pavle', 'Milos', 'Andrej', 'Relja', 'Bogdan', 'Sava', 'Djordje', 'Matija',
            'Hamza', 'Tarik', 'Amar', 'Adin', 'Emin', 'Dino', 'Faruk', 'Kenan',
        ];

        $prezimena = [
            'Ilic', 'Savic', 'Milic', 'Kostic', 'Djordjevic', 'Jovanovic', 'Petrovic', 'Nikolic',
            'Markovic', 'Stojanovic', 'Radovic', 'Pavlovic', 'Hadzic', 'Kucevic', 'Zukorlic', 'Mehmedovic',
        ];

        $birthYearBySelection = [
            'U9' => 2017,
            'U11' => 2015,
            'U13' => 2013,
            'U15' => 2011,
            'U17' => 2009,
            'U19' => 2007,
        ];

        $clanovi = [];
        $globalIndex = 1;

        foreach ($selekcije as $naziv => $selekcija) {
            for ($i = 0; $i < 16; $i++) {
                $ime = $imena[($globalIndex + $i) % count($imena)];
                $prezime = $prezimena[($globalIndex + ($i * 3)) % count($prezimena)];
                $email = 'demo.roditelj'.str_pad((string) $globalIndex, 3, '0', STR_PAD_LEFT).'@skola.rs';

                $clan = Clan::create([
                    'ime' => $ime,
                    'prezime' => $prezime,
                    'datum_rodjenja' => Carbon::create($birthYearBySelection[$selekcija->uzrasna_kategorija], 1 + ($i % 12), 2 + ($i % 24))->toDateString(),
                    'telefon_roditelja' => '064800'.str_pad((string) $globalIndex, 3, '0', STR_PAD_LEFT),
                    'email_roditelja' => $email,
                    'datum_uclanjenja' => Carbon::create(2025, 8 + ($i % 4), 1 + ($i % 20))->toDateString(),
                    'status_clana' => $i === 15 ? 'neaktivan' : ($i === 14 ? 'suspendovan' : 'aktivan'),
                    'selekcija_id' => $selekcija->id,
                ]);

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Roditelj '.$ime.' '.$prezime,
                        'password' => $password,
                        'role' => 'clan_roditelj',
                        'clan_id' => $clan->id,
                    ]
                );

                $clanovi[$naziv][] = $clan;
                $globalIndex++;
            }
        }

        return $clanovi;
    }

    private function createAdminUser(string $password): void
    {
        User::updateOrCreate(
            ['email' => 'demo.admin@skola.rs'],
            [
                'name' => 'Demo Administrator',
                'password' => $password,
                'role' => 'administrator',
            ]
        );
    }

    private function createTreninzi(array $selekcije, array $treneri, array $clanovi): void
    {
        $locations = ['Teren 1', 'Teren 2', 'Balon sala', 'Gradski stadion', 'Sportski centar'];

        foreach ($selekcije as $naziv => $selekcija) {
            for ($week = 0; $week < 12; $week++) {
                $trening = Trening::create([
                    'datum' => Carbon::create(2026, 3, 2)->addWeeks($week)->addDays($week % 3)->toDateString(),
                    'vreme' => (($week % 2) === 0 ? '18:00' : '19:30'),
                    'lokacija' => $locations[$week % count($locations)],
                    'selekcija_id' => $selekcija->id,
                    'trener_id' => $treneri[$naziv][$week % count($treneri[$naziv])]->id,
                ]);

                foreach ($clanovi[$naziv] as $index => $clan) {
                    $trening->prisustva()->create([
                        'clan_id' => $clan->id,
                        'prisutan' => (($index + $week) % 7) !== 0,
                    ]);
                }
            }
        }
    }

    private function createUtakmice(array $selekcije, array $treneri): array
    {
        $opponents = [
            'FK Novi Pazar', 'FK Josanica', 'FK Ras', 'FK Tutin', 'FK Sloga',
            'FK Rudar', 'FK Jedinstvo', 'FK Sjenica', 'FK Zlatibor', 'FK Sloboda',
        ];
        $locations = ['Gradski stadion', 'Sportski centar', 'Teren 1', 'Gostujuci teren', 'Turnirski kompleks'];
        $terrainTypes = ['domaci', 'gostujuci', 'neutral'];
        $utakmice = [];
        $matchIndex = 0;

        foreach ($selekcije as $naziv => $selekcija) {
            for ($round = 0; $round < 6; $round++) {
                $played = $round < 4;

                $utakmice[] = Utakmica::create([
                    'datum' => Carbon::create(2026, 4, 5)->addWeeks($round * 2)->addDays($matchIndex % 5)->toDateString(),
                    'vreme' => (($round % 2) === 0 ? '11:00' : '16:30'),
                    'protivnik' => $opponents[$matchIndex % count($opponents)],
                    'lokacija' => $locations[$matchIndex % count($locations)],
                    'tip_terena' => $terrainTypes[$matchIndex % count($terrainTypes)],
                    'selekcija_id' => $selekcija->id,
                    'trener_id' => $treneri[$naziv][$round % count($treneri[$naziv])]->id,
                    'golovi_domacin' => $played ? (($matchIndex + 2) % 5) : null,
                    'golovi_gost' => $played ? (($matchIndex + 4) % 4) : null,
                ]);

                $matchIndex++;
            }
        }

        return $utakmice;
    }

    private function createTimoviINastupi(array $utakmice, array $clanovi): void
    {
        $comments = [
            'Stabilan nastup i dobra disciplina u presingu.',
            'Odlicno kretanje bez lopte i pravovremeno otvaranje.',
            'Potrebno vise koncentracije u zavrsnici.',
            'Dobar doprinos u tranziciji i duel igri.',
            'Sigurna igra, mirna kontrola lopte i dobra komunikacija.',
        ];

        foreach ($utakmice as $matchIndex => $utakmica) {
            $selectionMembers = $clanovi[$utakmica->selekcija->naziv];
            $selectedMembers = array_slice($selectionMembers, 0, 14);

            $tim = Tim::create([
                'naziv' => $utakmica->selekcija->naziv.' vs '.$utakmica->protivnik,
                'utakmica_id' => $utakmica->id,
                'selekcija_id' => $utakmica->selekcija_id,
                'trener_id' => $utakmica->trener_id,
            ]);

            foreach ($selectedMembers as $index => $clan) {
                $tim->clanovi()->attach($clan->id, ['uloga' => $index < 11 ? 'starter' : 'rezerva']);

                if ($utakmica->golovi_domacin === null) {
                    continue;
                }

                NastupIgraca::create([
                    'clan_id' => $clan->id,
                    'utakmica_id' => $utakmica->id,
                    'odigrani_minuti' => $index < 11 ? 60 : 20 + (($index + $matchIndex) % 21),
                    'golovi' => ($index + $matchIndex) % 9 === 0 ? 1 : 0,
                    'asistencije' => ($index + $matchIndex) % 7 === 0 ? 1 : 0,
                    'zuti_karton' => ($index + $matchIndex) % 11 === 0,
                    'crveni_karton' => false,
                    'ocena_trenera' => 6 + (($index + $matchIndex) % 35) / 10,
                    'komentar_trenera' => $comments[($index + $matchIndex) % count($comments)],
                ]);
            }
        }
    }

    private function createClanarine(array $clanovi): void
    {
        foreach ($clanovi as $selectionMembers) {
            foreach ($selectionMembers as $index => $clan) {
                for ($month = 3; $month <= 6; $month++) {
                    Clanarina::create([
                        'clan_id' => $clan->id,
                        'iznos' => 3000,
                        'datum_od' => Carbon::create(2026, $month, 1)->toDateString(),
                        'datum_do' => Carbon::create(2026, $month, 1)->endOfMonth()->toDateString(),
                        'status_placanja' => (($index + $month) % 8 === 0)
                            ? 'na_cekanju'
                            : ((($index + $month) % 9 === 0) ? 'neplaceno' : 'placeno'),
                    ]);
                }
            }
        }
    }
}
