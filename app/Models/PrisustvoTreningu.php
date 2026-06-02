<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrisustvoTreningu extends Model
{
    protected $table = 'prisustvo_treningu';

    protected $fillable = ['trening_id', 'clan_id', 'prisutan'];

    protected $casts = ['prisutan' => 'boolean'];

    public function trening()
    {
        return $this->belongsTo(Trening::class);
    }

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }
}
