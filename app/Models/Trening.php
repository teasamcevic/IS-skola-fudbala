<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trening extends Model
{
    protected $table = 'treninzi';

    protected $fillable = ['datum', 'vreme', 'lokacija', 'selekcija_id', 'trener_id'];

    public function selekcija()
    {
        return $this->belongsTo(Selekcija::class);
    }

    public function trener()
    {
        return $this->belongsTo(Trener::class);
    }

    public function prisustva()
    {
        return $this->hasMany(PrisustvoTreningu::class);
    }
}
