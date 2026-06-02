<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utakmice', function (Blueprint $table) {
            $table->id();
            $table->date('datum');
            $table->time('vreme');
            $table->string('protivnik', 100);
            $table->string('lokacija', 100);
            $table->enum('tip_terena', ['domaci', 'gostujuci', 'neutral']);
            $table->foreignId('selekcija_id')->constrained('selekcije')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('trener_id')->constrained('treneri')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('golovi_domacin')->nullable();
            $table->unsignedInteger('golovi_gost')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utakmice');
    }
};
