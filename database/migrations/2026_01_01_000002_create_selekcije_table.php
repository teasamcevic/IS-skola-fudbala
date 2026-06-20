<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('selekcije', function (Blueprint $table) {
            $table->id();
            $table->string('naziv', 50)->unique();
            $table->string('uzrasna_kategorija', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selekcije');
    }
};
