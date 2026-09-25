<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $table = 'setoran';

    protected $fillable = [
        'santri_id',
        'musyrif_id',
        'juz',
        'surah',
        'ayat_awal',
        'ayat_akhir',
        'jenis',
        'nilai',
        'catatan',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function musyrif()
    {
        return $this->belongsTo(User::class, 'musyrif_id');
    }
}
