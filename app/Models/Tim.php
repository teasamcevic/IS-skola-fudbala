<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    protected $table = 'timovi';

    protected $fillable = ['naziv', 'utakmica_id', 'selekcija_id', 'trener_id'];

    public function utakmica()
    {
        return $this->belongsTo(Utakmica::class);
    }

    public function selekcija()
    {
        return $this->belongsTo(Selekcija::class);
    }

    public function trener()
    {
        return $this->belongsTo(Trener::class);
    }

    public function clanovi()
    {
        return $this->belongsToMany(Clan::class, 'clan_tima')->withPivot('uloga')->withTimestamps();
    }
}
