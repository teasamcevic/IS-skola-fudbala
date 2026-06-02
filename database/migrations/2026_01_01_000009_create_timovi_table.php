<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('timovi', function (Blueprint $table) {
            $table->id();
            $table->string('naziv', 50);
            $table->foreignId('utakmica_id')->unique()->constrained('utakmice')->cascadeOnDelete();
            $table->foreignId('selekcija_id')->constrained('selekcije')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('trener_id')->constrained('treneri')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timovi');
    }
};
