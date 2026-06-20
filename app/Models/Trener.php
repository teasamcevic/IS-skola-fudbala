<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trener extends Model
{
    protected $table = 'treneri';

    protected $fillable = ['ime', 'prezime', 'datum_rodjenja', 'telefon', 'licenca', 'datum_zaposlenja', 'selekcija_id'];

    // Potrebno Angular klijentu kada se model serijalizuje u JSON.
    protected $appends = ['puno_ime'];

    public function getPunoImeAttribute(): string
    {
        return "{$this->ime} {$this->prezime}";
    }

    public function selekcija()
    {
        return $this->belongsTo(Selekcija::class);
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
