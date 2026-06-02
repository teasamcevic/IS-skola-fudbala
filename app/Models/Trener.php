<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trener extends Model
{
    protected $table = 'treneri';

    protected $fillable = ['ime', 'prezime', 'datum_rodjenja', 'telefon', 'licenca', 'datum_zaposlenja'];

    public function getPunoImeAttribute(): string
    {
        return "{$this->ime} {$this->prezime}";
    }

    public function selekcije()
    {
        return $this->hasMany(Selekcija::class);
    }

    public function treninzi()
    {
        return $this->hasMany(Trening::class);
    }

    public function utakmice()
    {
        return $this->hasMany(Utakmica::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
