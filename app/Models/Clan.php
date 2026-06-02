<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    protected $table = 'clanovi';

    protected $fillable = [
        'ime',
        'prezime',
        'datum_rodjenja',
        'telefon_roditelja',
        'email_roditelja',
        'datum_uclanjenja',
        'status_clana',
        'selekcija_id',
    ];

    public function getPunoImeAttribute(): string
    {
        return "{$this->ime} {$this->prezime}";
    }

    public function selekcija()
    {
        return $this->belongsTo(Selekcija::class);
    }

    public function prisustva()
    {
        return $this->hasMany(PrisustvoTreningu::class);
    }

    public function timovi()
    {
        return $this->belongsToMany(Tim::class, 'clan_tima')->withPivot('uloga')->withTimestamps();
    }

    public function nastupi()
    {
        return $this->hasMany(NastupIgraca::class);
    }

    public function clanarine()
    {
        return $this->hasMany(Clanarina::class);
    }
}
