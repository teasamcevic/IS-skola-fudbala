<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utakmica extends Model
{
    protected $table = 'utakmice';

    protected $fillable = [
        'datum',
        'vreme',
        'protivnik',
        'lokacija',
        'tip_terena',
        'selekcija_id',
        'trener_id',
        'golovi_domacin',
        'golovi_gost',
    ];

    public function getRezultatAttribute(): string
    {
        if ($this->golovi_domacin === null || $this->golovi_gost === null) {
            return 'Nije odigrana';
        }

        return $this->golovi_domacin.' : '.$this->golovi_gost;
    }

    public function selekcija()
    {
        return $this->belongsTo(Selekcija::class);
    }

    public function trener()
    {
        return $this->belongsTo(Trener::class);
    }

    public function tim()
    {
        return $this->hasOne(Tim::class);
    }

    public function nastupi()
    {
        return $this->hasMany(NastupIgraca::class);
    }
}
