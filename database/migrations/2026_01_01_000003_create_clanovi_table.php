<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clanovi', function (Blueprint $table) {
            $table->id();
            $table->string('ime', 30);
            $table->string('prezime', 30);
            $table->date('datum_rodjenja');
            $table->string('telefon_roditelja', 20);
            $table->string('email_roditelja', 100)->nullable();
            $table->date('datum_uclanjenja');
            $table->enum('status_clana', ['aktivan', 'neaktivan', 'suspendovan'])->default('aktivan');
            $table->foreignId('selekcija_id')->nullable()->constrained('selekcije')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clanovi');
    }
};
