<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('treninzi', function (Blueprint $table) {
            $table->id();
            $table->date('datum');
            $table->time('vreme');
            $table->string('lokacija', 100);
            $table->foreignId('selekcija_id')->constrained('selekcije')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('trener_id')->constrained('treneri')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treninzi');
    }
};
