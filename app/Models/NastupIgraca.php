<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NastupIgraca extends Model
{
    protected $table = 'nastup_igraca';

    protected $fillable = [
        'clan_id',
        'utakmica_id',
        'odigrani_minuti',
        'golovi',
        'asistencije',
        'zuti_karton',
        'crveni_karton',
        'ocena_trenera',
        'komentar_trenera',
    ];

    protected $casts = [
        'zuti_karton' => 'boolean',
        'crveni_karton' => 'boolean',
        'ocena_trenera' => 'decimal:1',
    ];

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    public function utakmica()
    {
        return $this->belongsTo(Utakmica::class);
    }
}
