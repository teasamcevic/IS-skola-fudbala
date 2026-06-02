<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE clanovi DROP FOREIGN KEY clanovi_selekcija_id_foreign');
        DB::statement('ALTER TABLE clanovi MODIFY selekcija_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE clanovi ADD CONSTRAINT clanovi_selekcija_id_foreign FOREIGN KEY (selekcija_id) REFERENCES selekcije(id) ON UPDATE CASCADE ON DELETE RESTRICT');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $fallbackSelection = DB::table('selekcije')->orderBy('id')->value('id');

        if ($fallbackSelection) {
            DB::table('clanovi')->whereNull('selekcija_id')->update(['selekcija_id' => $fallbackSelection]);
        }

        DB::statement('ALTER TABLE clanovi DROP FOREIGN KEY clanovi_selekcija_id_foreign');
        DB::statement('ALTER TABLE clanovi MODIFY selekcija_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE clanovi ADD CONSTRAINT clanovi_selekcija_id_foreign FOREIGN KEY (selekcija_id) REFERENCES selekcije(id) ON UPDATE CASCADE ON DELETE RESTRICT');
    }
};
