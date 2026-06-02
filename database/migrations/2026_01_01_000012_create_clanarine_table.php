<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clanarine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clan_id')->constrained('clanovi')->cascadeOnDelete();
            $table->unsignedInteger('iznos');
            $table->date('datum_od');
            $table->date('datum_do');
            $table->enum('status_placanja', ['placeno', 'neplaceno', 'na_cekanju'])->default('neplaceno');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clanarine');
    }
};
