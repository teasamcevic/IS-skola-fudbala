<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about-project', function () {
    $this->info('Informacioni sistem skole fudbala.');
})->purpose('Prikazuje kratak opis projekta');
