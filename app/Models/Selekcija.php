<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selekcija extends Model
{
    protected $table = 'selekcije';

    protected $fillable = ['naziv', 'uzrasna_kategorija'];

    public function treneri()
    {
        return $this->hasMany(Trener::class);
    }

    public function getTreneriListaAttribute(): string
    {
        return $this->treneri->pluck('puno_ime')->join(', ') ?: 'Nema dodeljenih trenera';
    }

    public function clanovi()
    {
        return $this->hasMany(Clan::class);
    }

    public function treninzi()
    {
        return $this->hasMany(Trening::class);
    }

    public function utakmice()
    {
        return $this->hasMany(Utakmica::class);
    }

    public function timovi()
    {
        return $this->hasMany(Tim::class);
    }
}
