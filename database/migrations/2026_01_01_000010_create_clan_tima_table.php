<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clan_tima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_id')->constrained('timovi')->cascadeOnDelete();
            $table->foreignId('clan_id')->constrained('clanovi')->cascadeOnDelete();
            $table->enum('uloga', ['starter', 'rezerva'])->default('rezerva');
            $table->timestamps();
            $table->unique(['tim_id', 'clan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clan_tima');
    }
};
