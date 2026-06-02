<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('treneri', function (Blueprint $table) {
            $table->id();
            $table->string('ime', 30);
            $table->string('prezime', 30);
            $table->date('datum_rodjenja');
            $table->string('telefon', 20)->unique();
            $table->string('licenca', 50);
            $table->date('datum_zaposlenja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treneri');
    }
};
