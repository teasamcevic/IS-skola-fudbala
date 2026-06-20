<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CleanDemoSeeder extends Seeder
{
    private Carbon $now;

    public function run(): void
    {
        $this->now = now();

        DB::transaction(function () {
            $password = Hash::make('password');
            $treneri = $this->createTreneri();
            $selekcije = $this->createSelekcije($treneri);
            $this->connectTreneriToSelekcije($treneri, $selekcije);
            $clanovi = $this->createClanovi($selekcije);

            $this->createUsers($password, $treneri, $clanovi);
            $this->createTreninzi($treneri, $selekcije, $clanovi);
            $utakmice = $this->createUtakmice($treneri, $selekcije);
            $this->createTimoviINastupi($utakmice, $clanovi);
            $this->createClanarine($clanovi);
        });
    }

    private function createTreneri(): array
    {
        $rows = [
            ['Milan', 'Radovic', 'UEFA A', '063900001'],
            ['Marko', 'Petrovic', 'UEFA B', '063900002'],
            ['Nikola', 'Jovanovic', 'UEFA B', '063900003'],
            ['Emir', 'Hadzic', 'UEFA C', '063900004'],
        ];

        $treneri = [];

        foreach ($rows as $index => [$ime, $prezime, $licenca, $telefon]) {
            $data = [
                'ime' => $ime,
                'prezime' => $prezime,
                'datum_rodjenja' => Carbon::create(1982 + $index, 2 + $index, 10 + $index)->toDateString(),
                'telefon' => $telefon,
                'licenca' => $licenca,
                'datum_zaposlenja' => Carbon::create(2020 + $index, 8, 1)->toDateString(),
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            if (Schema::hasColumn('treneri', 'selekcija_id')) {
                $data['selekcija_id'] = null;
            }

            $treneri[] = $data + ['id' => DB::table('treneri')->insertGetId($data)];
        }

        return $treneri;
    }

    private function createSelekcije(array $treneri): array
    {
        $rows = [
            ['Petlici', 'U9'],
            ['Pioniri', 'U13'],
            ['Kadeti', 'U15'],
            ['Juniori', 'U17'],
        ];

        $selekcije = [];

        foreach ($rows as $index => [$naziv, $uzrast]) {
            $data = [
                'naziv' => $naziv,
                'uzrasna_kategorija' => $uzrast,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            if (Schema::hasColumn('selekcije', 'trener_id')) {
                $data['trener_id'] = $treneri[$index]['id'];
            }

            $selekcije[] = $data + ['id' => DB::table('selekcije')->insertGetId($data)];
        }

        return $selekcije;
    }

    private function connectTreneriToSelekcije(array $treneri, array $selekcije): void
    {
        if (! Schema::hasColumn('treneri', 'selekcija_id')) {
            return;
        }

        foreach ($treneri as $index => $trener) {
            DB::table('treneri')
                ->where('id', $trener['id'])
                ->update([
                    'selekcija_id' => $selekcije[$index]['id'],
                    'updated_at' => $this->now,
                ]);
        }
    }

    private function createClanovi(array $selekcije): array
    {
        $firstNames = ['Luka', 'Vuk', 'Ognjen', 'Stefan', 'Nemanja', 'Filip', 'Uroš', 'Pavle', 'Hamza', 'Tarik', 'Aleksa', 'Emir'];
        $lastNames = ['Ilić', 'Savić', 'Milić', 'Kostić', 'Jovanović', 'Petrović', 'Hadžić', 'Kučevac', 'Zukorlić', 'Radović', 'Nikolić', 'Đorđević'];
        $birthYears = ['U9' => 2017, 'U13' => 2013, 'U15' => 2011, 'U17' => 2009];
        $clanovi = [];
        $globalIndex = 1;

        foreach ($selekcije as $selekcija) {
            for ($i = 0; $i < 12; $i++) {
                $emailIndex = str_pad((string) $globalIndex, 3, '0', STR_PAD_LEFT);
                $data = [
                    'ime' => $firstNames[$i],
                    'prezime' => $lastNames[($i + $globalIndex) % count($lastNames)],
                    'datum_rodjenja' => Carbon::create($birthYears[$selekcija['uzrasna_kategorija']], 1 + ($i % 12), 5 + $i)->toDateString(),
                    'telefon_roditelja' => '064910'.$emailIndex,
                    'email_roditelja' => 'roditelj'.$globalIndex.'@skola.rs',
                    'datum_uclanjenja' => Carbon::create(2026, 1, 10 + ($i % 10))->toDateString(),
                    'status_clana' => 'aktivan',
                    'selekcija_id' => $selekcija['id'],
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ];

                $id = DB::table('clanovi')->insertGetId($data);
                $clanovi[$selekcija['id']][] = $data + ['id' => $id, 'selekcija' => $selekcija];
                $globalIndex++;
            }
        }

        return $clanovi;
    }

    private function createUsers(string $password, array $treneri, array $clanovi): void
    {
        $this->insertUser('Administrator', 'admin@skola.rs', $password, 'administrator');
        $this->insertUser('Demo Administrator', 'demo.admin@skola.rs', $password, 'administrator');

        foreach ($treneri as $index => $trener) {
            $number = $index + 1;
            $name = $trener['ime'].' '.$trener['prezime'];

            $this->insertUser($name, 'trener'.$number.'@skola.rs', $password, 'trener', trenerId: $trener['id']);
            $this->insertUser($name, 'demo.trener'.$number.'@skola.rs', $password, 'trener', trenerId: $trener['id']);
        }

        $parentNumber = 1;

        foreach ($clanovi as $selectionMembers) {
            foreach ($selectionMembers as $clan) {
                $name = 'Roditelj '.$clan['ime'].' '.$clan['prezime'];
                $this->insertUser($name, 'roditelj'.$parentNumber.'@skola.rs', $password, 'clan_roditelj', clanId: $clan['id']);
                $this->insertUser($name, 'demo.roditelj'.str_pad((string) $parentNumber, 3, '0', STR_PAD_LEFT).'@skola.rs', $password, 'clan_roditelj', clanId: $clan['id']);

                $parentNumber++;
            }
        }
    }

    private function insertUser(string $name, string $email, string $password, string $role, ?int $clanId = null, ?int $trenerId = null): void
    {
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => $this->now,
            'password' => $password,
            'role' => $role,
            'clan_id' => $clanId,
            'trener_id' => $trenerId,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ]);
    }

    private function createTreninzi(array $treneri, array $selekcije, array $clanovi): void
    {
        $locations = [
            'Gradski stadion Novi Pazar',
            'Sportski centar Pendik',
            'Stadion Šutenovac',
            'Balon sala Ras',
        ];

        foreach ($selekcije as $index => $selekcija) {
            for ($week = 0; $week < 6; $week++) {
                $treningId = DB::table('treninzi')->insertGetId([
                    'datum' => Carbon::create(2026, 6, 4)->addWeeks($week)->toDateString(),
                    'vreme' => $week % 2 === 0 ? '18:00' : '19:30',
                    'lokacija' => $locations[$week % count($locations)],
                    'selekcija_id' => $selekcija['id'],
                    'trener_id' => $treneri[$index]['id'],
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]);

                foreach ($clanovi[$selekcija['id']] as $memberIndex => $clan) {
                    DB::table('prisustvo_treningu')->insert([
                        'trening_id' => $treningId,
                        'clan_id' => $clan['id'],
                        'prisutan' => ($memberIndex + $week) % 5 !== 0,
                        'created_at' => $this->now,
                        'updated_at' => $this->now,
                    ]);
                }
            }
        }
    }

    private function createUtakmice(array $treneri, array $selekcije): array
    {
        $opponents = ['FK Novi Pazar', 'FK Josanica', 'FK Ras', 'FK Tutin'];
        $utakmice = [];

        foreach ($selekcije as $index => $selekcija) {
            for ($round = 0; $round < 4; $round++) {
                $utakmice[] = [
                    'id' => DB::table('utakmice')->insertGetId([
                        'datum' => Carbon::create(2026, 6, 8)->addWeeks($round)->addDays($index)->toDateString(),
                        'vreme' => $round % 2 === 0 ? '11:00' : '16:30',
                        'protivnik' => $opponents[($index + $round) % count($opponents)],
                        'lokacija' => $round % 2 === 0 ? 'Gradski stadion Novi Pazar' : 'Stadion protivničkog kluba',
                        'tip_terena' => $round % 2 === 0 ? 'domaci' : 'gostujuci',
                        'selekcija_id' => $selekcija['id'],
                        'trener_id' => $treneri[$index]['id'],
                        'golovi_domacin' => $round < 3 ? ($index + $round + 1) % 5 : null,
                        'golovi_gost' => $round < 3 ? ($index + $round) % 4 : null,
                        'created_at' => $this->now,
                        'updated_at' => $this->now,
                    ]),
                    'selekcija_id' => $selekcija['id'],
                    'trener_id' => $treneri[$index]['id'],
                    'selekcija_naziv' => $selekcija['naziv'],
                    'protivnik' => $opponents[($index + $round) % count($opponents)],
                    'played' => $round < 3,
                ];
            }
        }

        return $utakmice;
    }

    private function createTimoviINastupi(array $utakmice, array $clanovi): void
    {
        foreach ($utakmice as $matchIndex => $utakmica) {
            $teamId = DB::table('timovi')->insertGetId([
                'naziv' => $utakmica['selekcija_naziv'].' vs '.$utakmica['protivnik'],
                'utakmica_id' => $utakmica['id'],
                'selekcija_id' => $utakmica['selekcija_id'],
                'trener_id' => $utakmica['trener_id'],
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]);

            foreach (array_slice($clanovi[$utakmica['selekcija_id']], 0, 10) as $memberIndex => $clan) {
                DB::table('clan_tima')->insert([
                    'tim_id' => $teamId,
                    'clan_id' => $clan['id'],
                    'uloga' => $memberIndex < 8 ? 'starter' : 'rezerva',
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]);

                if (! $utakmica['played']) {
                    continue;
                }

                DB::table('nastup_igraca')->insert([
                    'clan_id' => $clan['id'],
                    'utakmica_id' => $utakmica['id'],
                    'odigrani_minuti' => $memberIndex < 8 ? 60 : 25,
                    'golovi' => ($memberIndex + $matchIndex) % 8 === 0 ? 1 : 0,
                    'asistencije' => ($memberIndex + $matchIndex) % 6 === 0 ? 1 : 0,
                    'zuti_karton' => ($memberIndex + $matchIndex) % 9 === 0,
                    'crveni_karton' => false,
                    'ocena_trenera' => 6.5 + (($memberIndex + $matchIndex) % 25) / 10,
                    'komentar_trenera' => 'Demo nastup za proveru napretka igraca.',
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]);
            }
        }
    }

    private function createClanarine(array $clanovi): void
    {
        foreach ($clanovi as $selectionMembers) {
            foreach ($selectionMembers as $memberIndex => $clan) {
                for ($month = 5; $month <= 7; $month++) {
                    DB::table('clanarine')->insert([
                        'clan_id' => $clan['id'],
                        'iznos' => 3000,
                        'datum_od' => Carbon::create(2026, $month, 1)->toDateString(),
                        'datum_do' => Carbon::create(2026, $month, 1)->endOfMonth()->toDateString(),
                        'status_placanja' => ($memberIndex + $month) % 7 === 0 ? 'na_cekanju' : 'placeno',
                        'created_at' => $this->now,
                        'updated_at' => $this->now,
                    ]);
                }
            }
        }
    }
}
