<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('treneri', function (Blueprint $table) {
            if (! Schema::hasColumn('treneri', 'selekcija_id')) {
                $table->foreignId('selekcija_id')->nullable()->after('datum_zaposlenja')->constrained('selekcije')->nullOnDelete()->cascadeOnUpdate();
            }
        });

        if (Schema::hasColumn('selekcije', 'trener_id')) {
            DB::table('selekcije')
                ->whereNotNull('trener_id')
                ->orderBy('id')
                ->get(['id', 'trener_id'])
                ->each(function ($selekcija) {
                    $trener = DB::table('treneri')->where('id', $selekcija->trener_id)->first(['id', 'selekcija_id']);

                    if ($trener && $trener->selekcija_id === null) {
                        DB::table('treneri')->where('id', $trener->id)->update(['selekcija_id' => $selekcija->id]);
                    }
                });

            Schema::table('selekcije', function (Blueprint $table) {
                $table->dropConstrainedForeignId('trener_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('selekcije', function (Blueprint $table) {
            if (! Schema::hasColumn('selekcije', 'trener_id')) {
                $table->foreignId('trener_id')->nullable()->constrained('treneri')->nullOnDelete()->cascadeOnUpdate();
            }
        });

        DB::table('treneri')
            ->whereNotNull('selekcija_id')
            ->orderBy('id')
            ->get(['id', 'selekcija_id'])
            ->each(function ($trener) {
                DB::table('selekcije')->where('id', $trener->selekcija_id)->whereNull('trener_id')->update(['trener_id' => $trener->id]);
            });

        Schema::table('treneri', function (Blueprint $table) {
            if (Schema::hasColumn('treneri', 'selekcija_id')) {
                $table->dropConstrainedForeignId('selekcija_id');
            }
        });
    }
};
