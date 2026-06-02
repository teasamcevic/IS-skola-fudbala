<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prisustvo_treningu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trening_id')->constrained('treninzi')->cascadeOnDelete();
            $table->foreignId('clan_id')->constrained('clanovi')->cascadeOnDelete();
            $table->boolean('prisutan')->default(false);
            $table->timestamps();
            $table->unique(['trening_id', 'clan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prisustvo_treningu');
    }
};
