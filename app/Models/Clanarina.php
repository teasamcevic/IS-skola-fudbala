<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clanarina extends Model
{
    protected $table = 'clanarine';

    protected $fillable = ['clan_id', 'iznos', 'datum_od', 'datum_do', 'status_placanja'];

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }
}
