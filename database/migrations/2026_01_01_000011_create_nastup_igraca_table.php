<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nastup_igraca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clan_id')->constrained('clanovi')->cascadeOnDelete();
            $table->foreignId('utakmica_id')->constrained('utakmice')->cascadeOnDelete();
            $table->unsignedInteger('odigrani_minuti')->default(0);
            $table->unsignedInteger('golovi')->default(0);
            $table->unsignedInteger('asistencije')->default(0);
            $table->boolean('zuti_karton')->default(false);
            $table->boolean('crveni_karton')->default(false);
            $table->decimal('ocena_trenera', 3, 1)->nullable();
            $table->text('komentar_trenera')->nullable();
            $table->timestamps();
            $table->unique(['clan_id', 'utakmica_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nastup_igraca');
    }
};
