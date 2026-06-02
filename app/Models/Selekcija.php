<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selekcija extends Model
{
    protected $table = 'selekcije';

    protected $fillable = ['naziv', 'uzrasna_kategorija', 'trener_id'];

    public function trener()
    {
        return $this->belongsTo(Trener::class);
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
